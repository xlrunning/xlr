<?php

namespace Lexing\TradeBundle\Exception;

/**
 * Class InvalidTradeException
 * @package Lexing\TradeBundle\Exception
 */
class InvalidTradeException extends \LogicException
{
    protected $message = '不合法的交易';
}