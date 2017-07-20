<?php

namespace Lexing\TradeBundle\Exception;

/**
 * Class TradeHandlerNotFoundException
 * @package Lexing\TradeBundle\Exception
 */
class TradeHandlerNotFoundException extends \LogicException
{
    protected $message = 'trade没有定义处理方法';
}