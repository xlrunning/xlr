<?php

namespace Lexing\TradeBundle\Event;

/**
 * TradeEvents
 *
 * @author kail
 */
class TradeEvents
{
    /**
     * 创建
     */
    const CREATE  = 'trade.create';

    const POST_CREATE = 'trade.post_create';

    /**
     * 支付
     */
    const PAID = 'trade.paid';
}
