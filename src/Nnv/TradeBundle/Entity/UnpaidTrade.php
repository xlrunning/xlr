<?php

namespace Nnv\TradeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nnv\TradeBundle\PaymentMethod\PaymentMethods;
use Nnv\TradeBundle\Model\TradeSubject;

/**
 * UnpaidTrade
 *
 * @ORM\Table(name="unpaid_trade")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class UnpaidTrade
{
    use TradeTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
}