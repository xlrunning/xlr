<?php

namespace Lexing\PaymentBundle\Service;

use Lexing\PaymentBundle\Gateway\PaymentGatewayInterface;
use Lexing\TradeBundle\Entity\Trade;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * PaymentManager
 *
 * @author kail
 */
class PaymentManager implements PaymentGatewayInterface
{
    use ContainerAwareTrait;

    /**
     * @var PaymentGatewayInterface
     */
    private $gateway;

    /**
     * Actual calls will be delegated to the gateway
     * @param PaymentGatewayInterface $gateway
     */
    public function __construct(PaymentGatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @param Trade $trade
     * @return mixed
     */
    public function putOrder(Trade $trade)
    {
        return $this->gateway->putOrder($trade);
    }

    /**
     * @param Trade $trade
     * @return mixed
     */
    public function queryOrder(Trade $trade)
    {
        return $this->gateway->queryOrder($trade);
    }

    /**
     * @param Trade $trade
     * @return mixed
     */
    public function refundOrder(Trade $trade)
    {
        return $this->gateway->refundOrder($trade);
    }

    /**
     * @param Trade $trade
     * @return mixed
     */
    public function queryRefund(Trade $trade)
    {
        return $this->gateway->queryRefund($trade);
    }

    /**
     *
     * @param Request $req
     * @return mixed
     */
    public function onNotify(Request $req)
    {
        $response = $this->gateway->onNotify($req);
        assert(is_a($response, Response::class));
        return $response;
    }

}