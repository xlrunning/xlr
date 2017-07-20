<?php

namespace Lexing\PaymentBundle\Exception;

/**
 * Class GatewayMethodNotSupportedException
 * @package Lexing\TradeBundle\Exception
 */
class GatewayMethodNotSupportedException extends \LogicException
{
    protected $message = '网关接口方法不支持';
}