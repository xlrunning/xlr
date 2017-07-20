<?php

namespace Lexing\PaymentBundle\Exception;

/**
 * Class PaymentMethodNotSupportedException
 * @package Lexing\TradeBundle\Exception
 */
class PaymentMethodNotSupportedException extends \LogicException
{
    protected $message = '支付方法不支持';
}