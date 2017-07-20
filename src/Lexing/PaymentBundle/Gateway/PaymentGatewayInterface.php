<?php

namespace Lexing\PaymentBundle\Gateway;

use Lexing\TradeBundle\Entity\Trade;
use Symfony\Component\HttpFoundation\Request;

interface PaymentGatewayInterface
{
    /**
     * 应该返回什么，JS和NATIVE（扫码）不同
     *
     * @param Trade $trade
     * @return mixed
     */
    public function putOrder(Trade $trade);

    public function queryOrder(Trade $trade);

    public function refundOrder(Trade $trade);

    public function queryRefund(Trade $trade); // @todo

    public function onNotify(Request $req);
}