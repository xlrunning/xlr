<?php

namespace Lexing\PaymentBundle\Gateway;

use Lexing\PaymentBundle\GatewayUtil;
use Lexing\TradeBundle\Entity\Trade;
use Lexing\PaymentBundle\Exception\PaymentMethodNotSupportedException;
use Lexing\PaymentBundle\PaymentMethods;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * SwiftPassGateway
 * 浦发（威富通）移动支付
 *
 * @author kail
 */
class SwiftPassGateway implements PaymentGatewayInterface
{
    use ContainerAwareTrait;
    
    private $gatewayUrl = 'https://pay.swiftpass.cn/pay/gateway';

    /**
     * 测试用商户号
     * @var string
     */
    private $mchId;

    /**
     * 密钥
     * @var string
     */
    private $mchKey;

    /**
     *
     * @param string $mchId
     * @param string $mchKey
     */
    public function __construct($mchId, $mchKey)
    {
        $this->mchId = $mchId;
        $this->mchKey = $mchKey;
    }

    /**
     * @param string $service
     * @param array $data
     * @return array
     */
    private function innerQuery($service, $data)
    {
        $req = $this->container->get('request_stack')->getCurrentRequest();
        $ip = $req->getClientIp();
        if (!$ip || strpos($ip, '::') === 0) {
            $ip = '127.0.0.1';
        }

        $data = array_merge($data, [
            'mch_id' => $this->mchId,
            'service' => $service,
            'nonce_str' => GatewayUtil::getNonceStr(),
            'mch_create_ip' => $ip,
        ]);
        $data['sign'] = GatewayUtil::makeSign($data, 'key', $this->mchKey);
        $ret = GatewayUtil::postData($this->gatewayUrl, $data);
        // status 0 通信成功，result_code 0 成功
        return $ret;
    }

    public function throwExceptionOnImproperResponse()
    {

    }

    /**
     * @param Trade $trade
     * @return array|null
     */
    public function putOrder(Trade $trade)
    {
        // @todo 参数的解释（从文档搬过来）

        // resovle service
        $paymentMethod = $trade->getPaymentMethod();
        $methodServiceMapping = [
            PaymentMethods::ALIPAY_NATIVE => 'pay.alipay.nativev2',
            PaymentMethods::WX_NATIVE => 'pay.weixin.native',
        ];

        if (!isset($methodServiceMapping[$paymentMethod])) {
            throw new PaymentMethodNotSupportedException();
        }
        $service = $methodServiceMapping[$paymentMethod];

        $resolver = new OptionsResolver();
        // @todo check trade's metadata?
        $resolver->setDefined([
            'op_user_id',
            'goods_tag',
            'device_info',
            'product_id',
            'attach',
            'time_start',
            'time_expire'
        ]);
        $resolver->setRequired([
            'body',
            'out_trade_no',
            'total_fee',
            'notify_url'
        ]);
        $data['out_trade_no'] = $trade->getTradeNo();
        $data['total_fee'] = $trade->getTotalFee();
        $data['body'] = $trade->getSummary();
        $notifyUrl = $this->container->get('router')
            ->generate('lx_payment_notify', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $data['notify_url'] = $notifyUrl;

        $resolver->resolve($data);

        $data = $resolver->resolve($data);
        $ret = $this->innerQuery($service, $data);

        // @todo exception should be notified to admin and dev
        if ($ret['status'] == 0 && $ret['result_code'] == 0) {
            return $ret['code_img_url'];
        }
        return null;
    }
    
    /**
     * 
     * @param Trade $trade
     * @return boolean
     * @throws InvalidOptionsException
     */
    public function queryOrder(Trade $trade)
    {
        $ret = $this->innerQuery('unified.trade.query', [
            'transaction_id' => $trade->getTransId()
        ]);
        return $ret['status'] == 0 && $ret['result_code'] == 0;
    }

    /**
     * 目前仅支持全款退
     *
     * @param int $totalFee
     * @param string $transId
     * @param string $outTradeNo
     * @return bool
     */
    public function refundOrder(Trade $trade)
    {
        $data['total_fee']     = $trade->getTotalFee();
        $data['refund_fee']    = $trade->getTotalFee();
        $data['out_refund_no'] = date('YmdHis') . mt_rand(9999, 99999);
        $ret = $this->innerQuery('unified.trade.refund', [
            'transaction_id' => $trade->getTransId()
        ]);
        return $ret['status'] == 0 && $ret['result_code'] == 0;
    }

    /**
     * @param Request $req
     * @return mixed
     */
    public function onNotify(Request $req)
    {
        try {
            $xmlEncoder = new XmlEncoder('xml');
            $arr = $xmlEncoder->decode($req->getBody());
        } catch (\Exception $e) {

        }
        if (!GatewayUtil::checkSign($arr['sign'], $arr)) {
            throw new \BadSignException('bad sign');
        }
        $tradeNo = $arr['out_trade_no'];
        $transId = $arr['transaction_id'];

        $tm = $this->container->get('lexing_trade.trade_manager');
        $ret = $tm->payTrade($tradeNo, $transId);
        return new Response($ret? 'success' : 'fail');
    }
    
    public function queryRefund(Trade $trade)
    {
        // unified.trade.refundquery
        $xml = '<xml><charset><![CDATA[UTF-8]]></charset> <fee_type><![CDATA[CNY]]></fee_type> <mch_id><![CDATA[7551000001]]></mch_id> <nonce_str><![CDATA[1490875862134]]></nonce_str> <openid><![CDATA[2088002110747631]]></openid> <out_trade_no><![CDATA[LX123456789]]></out_trade_no> <out_transaction_id><![CDATA[2017033021001004630219168483]]></out_transaction_id> <pay_result><![CDATA[0]]></pay_result> <result_code><![CDATA[0]]></result_code> <sign><![CDATA[5D3BAA1DFEE0DE6D354294743EDF2ED9]]></sign> <sign_type><![CDATA[MD5]]></sign_type> <status><![CDATA[0]]></status> <time_end><![CDATA[20170330201101]]></time_end> <total_fee><![CDATA[10]]></total_fee> <trade_type><![CDATA[pay.alipay.native]]></trade_type> <transaction_id><![CDATA[7551000001201703307131219603]]></transaction_id> <version><![CDATA[2.0]]></version> </xml>';
        $arr = $this->xmlDecode($xml);
        $ret = $this->checkSign($arr['sign'], $arr);
        dump($arr);
        exit;
    }
}