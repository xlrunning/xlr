<?php

namespace Lexing\TradeBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Lexing\TradeBundle\Entity\TradeInterface;
use Nnv\NotificationBundle\Event\NotifiableEvent;

/**
 * TradeEvent
 *
 * @author kail
 */
class TradeEvent extends NotifiableEvent
{
    /**
     *
     * @var Trade
     */
    private $trade;
    
    public function __construct(TradeInterface $trade)
    {
        parent::__construct($trade, ['trade' => $trade]);
        
        $this->trade = $trade;
    }
    
    /**
     *
     * @return Trade
     */
    public function setTrade(TradeInterface $trade)
    {
        $this->trade = $trade;
        
        return $this;
    }
    
    /**
     *
     * @return Trade
     */
    public function getTrade()
    {
        return $this->trade;
    }
}