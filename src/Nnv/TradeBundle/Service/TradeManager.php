<?php

namespace Nnv\TradeBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
# use Nnv\UserBundle\Entity\User;
use Nnv\TradeBundle\Entity\Trade;
# use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Nnv\TradeBundle\Event\TradeEvents;
use Nnv\TradeBundle\Event\TradeEvent;
# use Nnv\AccountBundle\Entity\AccountLog;
use Nnv\TradeBundle\PaymentMethod\PaymentMethods;
use Symfony\Component\HttpFoundation\Request;

/**
 * 
 * TradeManager
 * 
 * @todo 获取不同payment gateway进行支付、退款等操作
 * @todo 未来支持席位锁定、库存还原
 */
class TradeManager
{
    /**
     * 
     * @var ContainerInterface
     */
    private $container;
    
    /**
     * Method inject service container
     * 
     * @param ConatainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    // @todo isBalanceSupported
    
    /**
     * @todo pass in entity class
     * @todo user should NOT be required
     * 
     * @param string $type
     * @param Request $req
     * @param User $user
     * @return Trade
     */
    public function createTrade($type, $user = null, Request $req = null)
    {
        $resolvedTo = $this->container->getParameter('nnv.resolved_to');
        $tradeEntityClass = $resolvedTo['Nnv\\TradeBundle\\Entity\\Trade'];
        $trade = new $tradeEntityClass();
        $trade
                ->setType($type)
                ->setTradeNo($this->createTradeNo());
                //->setUser($user);
        
        if (!$req) {
            $req = $this->container->get('request_stack')->getCurrentRequest();
        }
        $event = new TradeEvent($trade);
        $event->setRequest($req);
        // 由事件监听设置交易的金额、数据等
        $this->container->get('event_dispatcher')->dispatch(TradeEvents::getBuildEventName($type), $event);
        assert($trade->getTotalFee() > 0);
        
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($trade);
        $em->flush();
        
        return $trade;
    }
    
    /**
     * 24位的一个订单号(微信要求32位以内)
     * 
     * @return string
     */
    public function createTradeNo()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < 10; ++$i)  {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        $str = date('YmdHis') . $str;
        return $str;
    }
    
    /**
     * 
     * @param string $tradeNo
     * @return Trade
     */
    public function getTradeByNo($tradeNo)
    {
        if (strpos($tradeNo, '_') !== false) {
            $tradeNo = substr($tradeNo, 0, strpos($tradeNo, '_'));
        }
        $em = $this->container->get('doctrine.orm.entity_manager');
        $resolvedTo = $this->container->getParameter('nnv.resolved_to');
        $tradeEntityClass = $resolvedTo['Nnv\\TradeBundle\\Entity\\Trade'];
        $repo = $em->getRepository($tradeEntityClass);
        
        return $repo->findOneBy(['tradeNo' => $tradeNo]);
    }
    
    /**
     * 新版微信支付
     * 
     * @todo jsapi or native
     * 
     * @param Trade $trade
     * @param string $openid
     * @param bool $test
     * @return array
     */
    public function createJsApiParameters(Trade $trade, $openid, $test = false)
    {
        $container = $this->container;
        $logger = $container->get('logger');
        
        // @todo $trade->getPaymentMethod === 'wx.jsapi'
        
        $wxpay = $this->container->get('nnv_wx.pay');
        $fee = $test ? 1 : $trade->getTotalFee()*100;
        $notifyUrl = $container->get('router')->generate('nnv_wx_notify_onpay', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $retArr = $wxpay->putOrder([
            'attach' => 'nnv',
            'body' => $trade->getSummary(),
            // 如果不追加随机数，当修改订单价格后将无法支付原订单
            // @todo $trade->isFeeAdjustable ? '' : '_' . mt_rand(1000, 9999)
            'out_trade_no' => $trade->getTradeNo(),
            'total_fee' => intval($fee),
            'notify_url' => $notifyUrl,
            'openid' => $openid
        ]);
        if (!$retArr) {
            throw new \Exception('微信支付下单失败');
        }
        $prepayId = $retArr['prepay_id']; // 有效期2小时
        return $wxpay->createJsApiParameters($prepayId);
    }
    
    /**
     * 用户发起支付流程时构造支付需要的相关数据，
     * Controller依据返回结果构造json response
     * 
     * @todo 先创建trade后选择支付方式支付
     * 
     * @param Trade $trade
     * @throws \LogicException
     * @throws \Exception
     */
    public function preparePayment($trade, $paymentMethod)
    {   
        if (!$this->container->get('nnv_wx.helper')->isInWechat() && PaymentMethods::belongToWx($paymentMethod)) {
            throw new \Exception('请在微信中访问'); // @todo if native then scan qr
        }
        
        // @todo validate $paymentMethod(supported or not)
        // @todo 返回的结果除ok和trade外其他参数应该由event handler（依据paymentMethod）决定
        // @todo 根据配置是否一定要用户登入
        // $logger = $this->container->get('logger');
        $ret = [];
        try {
            $paidByBalance = $paymentMethod == PaymentMethods::BALANCE;
            if ($paidByBalance && $trade->getType() == 'deposit') {
                throw new \LogicException('逻辑错误，请联系技术人员');
            }
            $user = $trade->getUser();
            if ($paidByBalance && $user->getBalance() < $trade->getTotalFee()) {
                throw new \Exception('余额不足');
            }
            // @todo more checks, wx.?? requires openid
            $trade
                    ->setPaymentMethod($paymentMethod)
                    ->setStateToBePaid();
            
            $em = $this->container->get('doctrine.orm.entity_manager');
            $em->persist($trade);
            $em->flush();
            
            $ret['trade'] = $trade->getId();
            if ($paymentMethod == PaymentMethods::WX_JSAPI) {
                $ret['jsApiParameters'] = $this->createJsApiParameters($trade, $user->getOpenid());
            } else if ($paidByBalance) {
                // 余额直接支付，先扣除余额再发出事件，和第三方支付方式相同
                $this->payTrade($trade, date('YmdHis') . strtoupper(uniqid()));
            }
        } catch (\Exception $e) { // only catch app's exceptions
            throw $e;
        }
        
        return $ret;
    }
    
    /**
     * 第三方支付回调或者直接余额支付
     * 
     * @param Trade $trade
     * @param string $transId
     */
    public function payTrade(Trade $trade, $transId)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->transactional(function($em) use ($trade, $transId) {
            $trade->setStatePaid()
                    ->setTransId($transId)
                    ->setPaidAt(new \DateTime());
            if ($trade->getPaymentMethod() === PaymentMethods::BALANCE) {
                $user     = $trade->getUser(); // $user !== null
                $totalFee = $trade->getTotalFee();
                // $accountLog = new AccountLog($trade);
                // @todo allow app to change summary
                // 根据trade.type来设置summary
                // $accountLog->setSummary('余额支付' . $totalFee);
                // $em->persist($accountLog);
                $user->consume($totalFee);
            }
        });
        
        $dispatcher = $this->container->get('event_dispatcher');
        try {
            $dispatcher->dispatch(TradeEvents::getPaidEventName($trade->getType()), new TradeEvent($trade));
        } catch (\Exception $e) {
            $logger = $this->container->get('logger');
            $this->revokeTrade($trade);
            $logger->crit('由于 ' . $e->getMessage() . '，交易' . $trade . ' 撤销');
            
            throw $e;
        }
    }
    
    /**
     * transaction, do your work in func
     * 
     * @param Trade $trade
     * @param callable $func
     * @throws \InvalidArgumentException
     */
    public function applicateTrade($trade, $func)
    {
        if (!is_callable($func)) {
            throw new \InvalidArgumentException('Expected argument of type "callable", got "' . gettype($func) . '"');
        }
        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->transactional(function($em) use ($func, $trade) {
            $trade->setStateApplicated();
            call_user_func($func, $em);
        });
    }
    
    /**
     * 撤销交易
     * 退款（已经被应用的交易不应该撤销）
     * 
     * @param Trade $trade
     * @return boolean
     */    
    public function revokeTrade($trade)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        if (!$trade->isPaid() || $trade->isRevoked()) {
            throw new \Exception('交易未付款或者已撤销');
        }
        $trade->setState(Trade::STATE_TOBEREVOKED);
        $em->flush();
        
        $logger = $this->container->get('logger');
        $callable = function($em) use ($trade) {
            $ret  = true;
            $user = $trade->getUser(); // $user !== null
            $totalFee = $trade->getTotalFee();
            $method = $trade->getPaymentMethod();
            if ($method === PaymentMethods::BALANCE) {
                $user->deposit($totalFee);
            } else if ($method === PaymentMethods::WX_JSAPI) {
                $wxpay = $this->container->get('nnv_wx.pay');
                $ret = $wxpay->refundOrder([
                    'out_refund_no' => $trade->getTradeNo() . 'X',
                    'total_fee' => $trade->getTotalFee()*100,
                    'transaction_id' => $trade->getTransId()
                ]);
            }
            if ($ret) {
                // $accountLog = new AccountLog($trade);
                // $accountLog->setSummary('交易撤回' . $totalFee);
                // $em->persist($accountLog);
                $trade->setState(Trade::STATE_REVOKED);
            }
        };
        try {
            $em->transactional($callable);
        } catch (\Exception $e) {
            $logger->error('----- revoke trade failed ' . $e->getMessage());
            throw new \Exception('交易撤回失败');
        }
        
        return true;
    }
    
    /**
     * 退款,默认退全款
     * 
     * @param Trade $trade
     * @param float $fee
     * @return boolean
     */
    public function refund($trade, $fee = null)
    {
        $logger = $this->container->get('logger');
        $logger->error('----- do refund');
        // @todo 检查交易时间半年以内
        if ($fee > $trade->getTotalFee()) {
            throw new \Exception('退款金额不能超过原订单支付金额！');
        }
        if ($fee === 0) {
            throw new \Exception('退款金额不能为0！');
        }
        if ($fee === null) {
            $fee = $trade->getTotalFee();
        }
        
        $env = $this->container->get('kernel')->getEnvironment();
        if ($env == 'dev') {
            $ret = ['return_code' => 'SUCCESS', 'result_code' => 'SUCCESS'];
        } else {
            // $wxDataRefund->SetRefund_Account('REFUND_SOURCE_RECHARGE_FUNDS');
            // 先尝试从1退再从2退
            
            try {
                $wxpay = $this->container->get('nnv_wx.pay');
                $ret = $wxpay->refundOrder([
                    'total_fee' => $trade->getTotalFee()*100,
                    'out_refund_no' => $trade->getTradeNo() . 'X',
                    'transaction_id' => $trade->getTransId()
                ]);
            } catch (\Exception $e) {
                $ret['return_code'] = 'FAIL';
                $logger->error('------------' . $e->getMessage());
            }
            
            $logger->error('------------'.json_encode($ret));
        }
        
        if ($ret['return_code'] == 'SUCCESS' && $ret['result_code'] == 'SUCCESS') {
            // refund_id
            return true;
        }
        $logger->error(json_encode($ret));
        throw new \Exception('退款失败');
    }
    
    // @todo 查询订单
    // @todo 查询退款情况(笔数)
    
    /**
     * 企业付款
     * 
     * ◆ 给同一个实名用户付款，单笔单日限额2W/2W
     * ◆ 给同一个非实名用户付款，单笔单日限额2000/2000
     * ◆ 一个商户同一日付款总额限额100W
     * 
     * @param string $openid
     * @param float $amount
     * @param string $realname
     */
    public function mchPay($openid, $amount, $realname = null)
    {
        $env = $this->container->get('kernel')->getEnvironment();
        if ($env == 'dev') {
            // return true;
        }
        
        $req = $this->container->get('request_stack')->getCurrentRequest();
        $ip = $req->getClientIp();
        if (!$ip || strpos($ip, '::') === 0) {
            $ip = '127.0.0.1';
        }
        
        $mchPayData = new \WxPayMchPay($this->container->getParameter('wx_partnerKey'), date('YmdHis') . uniqid(), ''.$amount*100, $openid, $realname, '提现', $ip);
        $logger = $this->container->get('logger');
        $ret = $this->container->get('wxpay.api')->mchpay($mchPayData, 6, $logger);
        if ($ret['return_code'] == 'SUCCESS' && $ret['result_code'] == 'SUCCESS') {
            return true;
        }
        // throw exception?
        $logger->error(json_encode($ret));
        return false;
    }
}