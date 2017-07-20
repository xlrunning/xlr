<?php

namespace Nnv\WxBundle\Service;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * WxPay
 * 
 * @see https://pay.weixin.qq.com/wiki/doc/api/index.html
 * @author kail
 */
class WxPay
{
    /**
     *
     * @var ContainerInterface
     */
    private $container;
    
    /**
     *
     * @var XmlEncoder
     */
    private $xmlEncoder;
    
    public function __construct(ContainerInterface $container)
    {
        $this->xmlEncoder = new XmlEncoder('xml');
        $this->container = $container;
    }
    
    private function makeSign(array $data)
    {
        ksort($data);
        unset($data['sign']);
        $string = array_reduce(array_keys($data), function($carry, $key) use ($data) {
            if ($data[$key] != '' && !is_array($data[$key])) {
                return $carry .= $key . '=' . $data[$key] . '&';
            }
            return $carry;
        });
        $string = $string . 'key=' . $this->container->getParameter('wx_partnerKey');
        return strtoupper(md5($string));
    }
    
    private function checkSign($sign, array $data)
    {
        return $sign == $this->makeSign($data);
    }
    
    /**
     * 微信支付接口"提交和返回数据都为XML格式，根节点名为xml"
     * @param string $url
     * @param array $data
     * @return array
     */
    private function postData($url, array $data)
    {
        $postBody = $this->xmlEncoder->encode($data, 'xml');
        
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, $postBody);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($c);
        return $this->xmlEncoder->decode($response, 'xml');
    }
    
    private function getNonceStr($length = 16) 
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
        $str = "";
        for ($i = 0; $i < $length; $i++) {  
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
        } 
        return $str;
    }
    
    private function getOptionResolver()
    {
        
    }
    
    /**
     * 
     * @param array $data
     * @return string
     */
    private function xmlEncode(array $data)
    {
        return $this->xmlEncoder->encode($data, 'xml');
    }
    
    /**
     * 
     * @param string $xml
     * @return array
     */
    private function xmlDecode($xml)
    {
        return $this->xmlEncoder->decode($xml, 'xml');
    }
    
    /**
     * 
     * @param array $data
     * @return array|null
     */
    public function putOrder($data)
    {
        $req = $this->container->get('request_stack')->getCurrentRequest();
        $ip = $req->getClientIp();
        if (!$ip || strpos($ip, '::') === 0) {
            $ip = '127.0.0.1';
        }        
        $resolver = new OptionsResolver();
        $resolver->setDefaults(array(
            'appid' => '' . $this->container->getParameter('wx_appid'),
            'mch_id' => '' . $this->container->getParameter('wx_partnerID'),
            'device_info' => 'WEB', // 终端设备号(门店号或收银设备ID)，PC网页或公众号内支付可以传"WEB"
            'nonce_str' => $this->getNonceStr(),
            'sign_type' => 'MD5',
            // 'body' => '测试商品',
            // 'detail' => '测试商品详情',
            'fee_type' => 'CNY',
            // 'total_fee' => 100, // 整数
            'spbill_create_ip' => $ip,
            // 'time_start' => 'yyyyMMddHHmmss',
            // 'time_expire' => 'yyyyMMddHHmmss',
            'goods_tag' => 'WXG', // 商品标记，使用代金券或立减优惠功能时需要的参数
            'trade_type' => 'JSAPI',
        ))->setDefined(['out_trade_no', 'openid', 'limit_pay', 'product_id', 'attach', 'time_start', 'time_expire']);
        $resolver->setRequired(['appid', 'mch_id', 'nonce_str', 'body', 'out_trade_no', 'total_fee', 'spbill_create_ip', 'notify_url', 'trade_type']);
        $resolver->setAllowedTypes('total_fee', 'int');
        $resolver->setAllowedValues('trade_type', ['JSAPI', 'NATIVE', 'APP']);
        $resolver->setNormalizer('trade_type', function(Options $options, $value){
            if ($value == 'JSAPI' && empty($options['openid'])) {
                throw new InvalidOptionsException('trade_type=JSAPI时（即公众号支付），openid必传');
            }
            if ($value == 'NATIVE' && empty($options['product_id'])) {
                throw new InvalidOptionsException('trade_type=NATIVE时（即扫码支付），product_id必传');
            }
            return $value;
        });
        // openid长度
        
        $data = $resolver->resolve($data);
        $data['sign'] = $this->makeSign($data);
        $ret = $this->postData('https://api.mch.weixin.qq.com/pay/unifiedorder', $data);
        
        // @todo exception should be notified to admin and dev
        //  "prepay_id" => "wx20170120093842098aaf0e550803322846"
        //  "trade_type" => "JSAPI"
        
        return $ret['return_code'] == 'SUCCESS' && $ret['result_code'] == 'SUCCESS' ? $ret : null;
    }
    
    /**
     * 统一下单接口返回的prepay_id参数值，putOrder返回
     * 
     * @param string $prepayId
     * @return array
     */
    public function createJsApiParameters($prepayId)
    {
        $data = [
            'appId' => $this->container->getParameter('wx_appid'),
            'timeStamp' => '' . time(),
            'nonceStr' => $this->getNonceStr(),
            'package' => "prepay_id=$prepayId",
            'signType' => 'MD5'
        ];
        $data['paySign'] = $this->makeSign($data);
        return $data;
    }
    
    /**
     * 
     * @param string $transId
     * @return boolean
     * @throws InvalidOptionsException
     */
    public function queryOrder($transId)
    {   
        $resolver = new OptionsResolver();
        $resolver->setDefaults(array(
            'appid' => '' . $this->container->getParameter('wx_appid'),
            'mch_id' => '' . $this->container->getParameter('wx_partnerID'),
            'transaction_id' => $transId,
            'nonce_str' => $this->getNonceStr(),
            'sign_type' => 'MD5',
        ))->setDefined(['transaction_id', 'out_trade_no']);
        $resolver->setRequired(['appid', 'mch_id', 'nonce_str']);
        $data = $resolver->resolve(['transaction_id' => $transId]);
        $data['sign'] = $this->makeSign($data);
        
        $ret = $this->postData('https://api.mch.weixin.qq.com/pay/orderquery', $data);
        // if($this->checkSign($ret['sign'], $ret));
        //  "trade_state" => "NOTPAY"
        //  "trade_state_desc" => "订单未支付"
        return $ret['return_code'] == 'SUCCESS' && $ret['result_code'] == 'SUCCESS';
    }
    
    public function refundOrder($data)
    {
        if ((!isset($data['transaction_id']) && !isset($data['out_trade_no'])) || (isset($data['transaction_id']) && isset($data['out_trade_no']))) {
            throw new InvalidOptionsException('transaction_id（微信订单号）和out_trade_no（商户订单号）二选一');
        }
        if (isset($data['total_fee']) && !isset($data['refund_fee'])) {
            $data['refund_fee'] = $data['total_fee'];
        }
        
        $resolver = new OptionsResolver();
        $resolver->setDefaults(array(
            'appid' => '' . $this->container->getParameter('wx_appid'),
            'mch_id' => '' . $this->container->getParameter('wx_partnerID'),
            'nonce_str' => $this->getNonceStr(),
            'sign_type' => 'MD5',
            'op_user_id' => $this->container->getParameter('wx_partnerID'),
        ))->setDefined(['transaction_id', 'out_trade_no', 'device_info', 'refund_fee_type', 'refund_account']);
        // out_refund_no 商户系统内部的退款单号，商户系统内部唯一，同一退款单号多次请求只退一笔
        $resolver->setRequired(['appid', 'mch_id', 'nonce_str', 'out_refund_no', 'total_fee', 'refund_fee']);
        $resolver->setAllowedTypes('total_fee', 'int')->setAllowedTypes('refund_fee', 'int');
        $data = $resolver->resolve($data);
        $data['sign'] = $this->makeSign($data);
        
        $ret = $this->postData('https://api.mch.weixin.qq.com/secapi/pay/refund', $data);
        return $ret;
    }
    
    public function queryRefund()
    {
        // https://api.mch.weixin.qq.com/pay/refundquery
    }
    
    /**
     * 
     * @see https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_7
     * @param string $postBody
     * @return array
     */
    public function notify($postBody)
    {
        $notifyArr = $this->xmlDecode($postBody);
        if($notifyArr['return_code'] != 'SUCCESS'){
            $logger = $this->container->get('logger');
            $logger->error('---- notify got but something wrong' . json_encode($notifyArr));
            return null;
        }
        // unset($notifyArr['return_code'], $notifyArr['return_msg']);
        if ($this->checkSign($notifyArr['sign'], $notifyArr)) {
            return $notifyArr;
        }
        // @todo sign fail
        return null;
    }
    
    /**
     * 微信支付通知返回用
     * 
     * @return Response
     */
    public function getNotifyResponse()
    {
        $response = new Response($this->xmlEncode(['return_code' => 'SUCCESS', 'return_msg' => 'OK']));
        $response->headers->set('Content-Type', 'application/xml; charset=utf-8');        
        return $response;
    }
}