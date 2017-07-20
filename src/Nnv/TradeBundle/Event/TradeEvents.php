<?php

namespace Nnv\TradeBundle\Event;

/**
 * TradeEvents
 *
 * @author kail
 */
class TradeEvents
{
    /**
     * 支付
     */
    const PAID = 'trade.paid';
    const PAID_BYBALANCE = 'trade.paid_bybalance';
    
    /**
     * 发出事件构造trade
     * 
     * @param string $type
     * @return type
     */
    public static function getBuildEventName($type)
    {
        return "trade.build_$type";
    }
    
    /**
     * trade支付成功
     * 
     * @param string $type
     * @return type
     */
    public static function getPaidEventName($type)
    {
        // assert($type !== 'bybalance')
        return "trade.paid_$type";
    }
}
