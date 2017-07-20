<?php

namespace Lexing\TradeBundle\Exception;

/**
 * Class TradeAlreadyPaidException
 * @package Lexing\TradeBundle\Exception
 */
class TradeAlreadyPaidException extends \LogicException
{
    protected $message = '交易已支付';
}