<?php

namespace Lexing\PaymentBundle\Gateway;

use Lexing\PaymentBundle\Exception\BadSignException;
use Lexing\PaymentBundle\Exception\GatewayMethodNotSupportedException;
use Lexing\PaymentBundle\GatewayUtil;
use Lexing\TradeBundle\Entity\Trade;
use Lexing\PaymentBundle\Exception\PaymentMethodNotSupportedException;
use Lexing\PaymentBundle\PaymentMethods;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * WangPosGateway
 * 旺POS支付
 *
 * @author kail
 */
class WangPosGateway implements PaymentGatewayInterface
{
    use ContainerAwareTrait;
    
    private $gatewayUrl = 'http://open.wangpos.com/wopengateway/api/entry';

    private $paymentMethodMappings = [
        '1001' => PaymentMethods::WPOS_CASH,
        '1003' => PaymentMethods::WPOS_WX,
        '1004' => PaymentMethods::WPOS_ALIPAY,
        '1006' => PaymentMethods::WPOS_UNIONPAY
    ];

    /**
     *
     */
    public function __construct()
    {
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
        throw new GatewayMethodNotSupportedException();
    }
    
    /**
     * @todo
     *
     * @param Trade $trade
     * @return boolean
     * @throws InvalidOptionsException
     */
    public function queryOrder(Trade $trade)
    {
//        $ret = $this->innerQuery('unified.trade.query', [
//            'transaction_id' => $trade->getTransId()
//        ]);
//        return $ret['status'] == 0 && $ret['result_code'] == 0;
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
        throw new GatewayMethodNotSupportedException();
    }

    /**
     * @todo
     *
     * @param Request $req
     * @return mixed
     */
    public function onNotify(Request $req)
    {
        $queryStr = $req->getContent();
        $params = [];
        parse_str($queryStr, $params);
        unset($params['sign_type']);
//        if (!GatewayUtil::checkSign($params['sign'], $params, 'key', $bpSecret)) {
//            throw new BadSignException('bad sign');
//        }

        $tradeNo = $params['out_trade_no'];
        // 第三方交易号，旺POS的为cashier_trade_no，如果是现金支付就没有thirdSerialNo
        $transId = isset($params['thirdSerialNo']) ? $params['thirdSerialNo'] : $params['cashier_trade_no'];
        $this->container->get('logger')->error('----- ' . $queryStr);
        $paymentMethod = isset($this->paymentMethodMappings[$params['pay_type']]) ? $this->paymentMethodMappings[$params['pay_type']] : PaymentMethods::WPOS_PENDING;
        $tm = $this->container->get('lexing_trade.trade_manager');
        $ret = $tm->payTrade($tradeNo, $transId, $paymentMethod, $params);
        return new Response('success');
    }
    
    public function queryRefund(Trade $trade)
    {
        throw new GatewayMethodNotSupportedException();
    }
}