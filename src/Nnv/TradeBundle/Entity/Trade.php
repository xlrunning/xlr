<?php

namespace Nnv\TradeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nnv\TradeBundle\PaymentMethod\PaymentMethods;
use Nnv\TradeBundle\Model\TradeSubject;

/**
 * Trade
 * 与支付相关，购买或者充值等。如果应用中无需充值仅是一种购买，可直接用订单扩展此类
 *
 * 
 * @todo 退款相关
 * 
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class Trade
{
    use TradeTrait;

    const STATE_PAID       = 'paid';
    const STATE_APPLICATED  = 'applicated';  // 一般是支付完成已和应用对象关联
    const STATE_TOBEREVOKED = 'toberevoked'; // 待撤销
    const STATE_REVOKED     = 'revoked';     // 已撤销

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * 状态
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255, nullable=false)
     */    
    private $state = self::STATE_PAID;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="paid_at", type="datetime", nullable=true)
     */
    protected $paidAt;
    
    // protected $openid;
    // 如果支持修改费用将会影响支付时的订单编号生成
    // $isFeeAdjustable

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Trade
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set paidAt
     *
     * @param \DateTime $paidAt
     *
     * @return Trade
     */
    public function setPaidAt($paidAt)
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    /**
     * Get paidAt
     *
     * @return \DateTime
     */
    public function getPaidAt()
    {
        return $this->paidAt;
    }

    public function isRevoked()
    {
        return $this->state === self::STATE_REVOKED;
    }
    
    public function setStatePaid()
    {
        $this->setState(self::STATE_PAID);
        
        return $this;
    }
    
    public function setStateToBePaid()
    {
        // $this->setState(self::STATE_TOBEPAID);
        
        return $this;        
    }
    
    public function setStateApplicated()
    {
        $this->setState(self::STATE_APPLICATED);
        
        return $this;
    }
    
    public function __toString()
    {
        return $this->tradeNo ? $this->tradeNo : '新订单';
    }
    
    // public function paidByBalance();
    
    /**
     * 
     * @param TradeSubject $tradeSubject
     * @return Trade
     */
    public function setTradeSubject(TradeSubject $tradeSubject)
    {
        $this->setMetaData($tradeSubject->toArray());
        
        return $this;
    }
}