<?php

namespace Nnv\TradeBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Nnv\TradeBundle\Entity\Trade;
use Nnv\NotificationBundle\Event\NotifiableEvent;

/**
 * TradeEvent
 * 
 * @todo move openid, wxjsapiparameters to subclass
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
    
    /**
     *
     * @var string
     */
    private $openid;
    
    /**
     *
     * @var Request
     */
    private $request;
    
    public function __construct(Trade $trade)
    {
        parent::__construct($trade, ['trade' => $trade]);
        
        $this->trade = $trade;
        $this->setUser($trade->getUser());
    }
    
    /**
     *
     * @return Trade
     */
    public function setTrade(Trade $trade)
    {
        $this->trade = $trade;
        
        return $this;
    }
    
    public function setRequest(Request $request)
    {
        $this->request = $request;
        
        return $this;
    }
    
    public function getRequest()
    {
        return $this->request;
    }    
    
    /**
     *
     * @return Trade
     */
    public function getTrade()
    {
        return $this->trade;
    }
    
    public function getOpenid()
    {
        return $this->openid;
    }
    
    public function setOpenid($openid)
    {
        $this->openid = $openid;
        
        return $this;
    }
}