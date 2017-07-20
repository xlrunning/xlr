<?php

namespace Lexing\TradeBundle\Service;

use Lexing\TradeBundle\Content\TradeContentInterface;
use Lexing\TradeBundle\Exception\TradeAlreadyPaidException;
use Lexing\TradeBundle\Payment\Payment;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Lexing\TradeBundle\Entity\Trade;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Lexing\TradeBundle\Event\TradeEvents;
use Lexing\TradeBundle\Event\TradeEvent;
use Lexing\PaymentBundle\PaymentMethods;
use Symfony\Component\HttpFoundation\Request;

/**
 * 
 * TradeManager
 * 本应用内目前仅为O2O支付
 */
class TradeManager
{
    use ContainerAwareTrait;

    private function em()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * 24位的一个订单号(微信要求32位以内)
     * 是否区分支付类型、用途...更好？
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
     * @param Payment $payment
     * @param TradeContentInterface $tradeContent
     * @return Trade
     */
    public function createTrade(Payment $payment, TradeContentInterface $tradeContent)
    {
        $tradeHandlerManager = $this->container->get('lexing_trade.trade_handler_manager');
        $tradeType = $tradeHandlerManager->getContentTradeType(get_class($tradeContent));
        assert($tradeType != null);

        $tradeHandler = $tradeHandlerManager->getHandler($tradeType);
        $contentData  = $tradeHandler->getContentTransformer()->transform($tradeContent);
        $trade = new Trade();
        $trade->setType($tradeType)
            ->setTradeNo($this->createTradeNo())
            ->setPayment($payment)
            ->setContentData($contentData);

        $event = new TradeEvent($trade);
        $dispatcher = $this->container->get('event_dispatcher');
        $dispatcher->dispatch(TradeEvents::CREATE, $event);

        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($trade);
        $em->flush();

        $dispatcher->dispatch(TradeEvents::POST_CREATE, $event);

        return $trade;
    }

    /**
     * 
     * @param string $tradeNo
     * @param boolean $paid
     * @return Trade
     */
    public function getTradeByNo($tradeNo, $paid = false)
    {
        if (strpos($tradeNo, '_') !== false) {
            $tradeNo = substr($tradeNo, 0, strpos($tradeNo, '_'));
        }
        $em   = $this->container->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('LexingTradeBundle:Trade');
        return $repo->findOneBy(['tradeNo' => $tradeNo]);
    }

    /**
     * 第三方支付回调
     * 
     * @param string $tradeNo
     * @param string $transId
     * @param string $paymentMethod
     * @param array $paymentMeta
     * @return Trade
     */
    public function payTrade($tradeNo, $transId, $paymentMethod = null, array $paymentMeta = [])
    {
        $trade = $this->getTradeByNo($tradeNo);
        if ($trade->isPaid()) {
            throw new TradeAlreadyPaidException();
        }

        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->transactional(function($em) use ($trade, $transId, $paymentMethod, $paymentMeta) {
            if ($paymentMethod) {
                $trade->setPaymentMethod($paymentMethod);
            }
            if (!empty($paymentMeta)) {
                $trade->setPaymentMeta($paymentMeta);
            }
            $trade->setStatePaid()
                ->setTransId($transId)
                ->setPaidAt(new \DateTime());
        });
        
        $dispatcher = $this->container->get('event_dispatcher');
        try {
            $dispatcher->dispatch(TradeEvents::PAID, new TradeEvent($trade));
        } catch (\Exception $e) {
            $logger = $this->container->get('logger');
            // $this->revokeTrade($trade);
            $logger->crit('由于 ' . $e->getMessage() . '，交易' . $trade . ' 撤销');
            
            throw $e;
        }

        return $trade;
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
            $totalFee = $trade->getTotalFee();
            $method = $trade->getPaymentMethod();
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
     * 退款，默认退全款
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

        // $logger->error(json_encode($ret));
        throw new \Exception('退款失败');
    }
}