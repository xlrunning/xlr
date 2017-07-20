<?php

namespace Nnv\TradeBundle\Model;

use Nnv\TradeBundle\Entity\Trade;

/**
 * TradeSubject
 * 主要用于保存应用数据用于支付完成后构造应用领域的对象
 *
 * @author kail
 */
abstract class TradeSubject
{
    /**
     *
     * @var Trade
     */
    protected $trade;
    
    /**
     * 从array转换成TradeSubject
     * 
     * @param Trade $trade
     */
    public function __construct(Trade $trade)
    {
        $this->trade = $trade;
    }
    
    protected function set($key, $val)
    {
        if ($this->trade->getId()) {
            throw new \Exception('existent trade cannot be modified');
        }
        $metaData = $this->trade->getMetaData();
        $metaData[$key] = $val;
        $this->trade->setMetaData($metaData);
        
        return $this;
    }
    
    protected function get($key)
    {
        $metaData = $this->trade->getMetaData();
        return isset($metaData[$key]) ? $metaData[$key] : null;
    }
    
    // @todo type, validate, signature?
}