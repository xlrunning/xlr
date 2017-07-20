<?php

namespace Lexing\TradeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lexing\TradeBundle\Payment\Payment;
use Lexing\PaymentBundle\PaymentMethod\PaymentMethods;

/**
 * Trade
 * 基础交易类，介于外部交易和内部业务之间
 *
 * @todo 退款相关
 *
 * @ORM\Table(name="trade", indexes={@ORM\Index(name="state_idx", columns={"state"}), @ORM\Index(name="type_idx", columns={"type"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Trade implements TradeInterface
{
    const STATE_CREATED     = 'created';
    const STATE_TOBEPAID   = 'tobepaid';
    const STATE_PAID        = 'paid';
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
    private $state = self::STATE_CREATED;

    /**
     * 交易的类型，用于应用内区分不同类型的交易如支付、充值、购买、抽奖等
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    protected $type;

    /**
     * 支付方式，影响退款
     * balance(should be reserved), wx.jsapi, wx.native, alipay
     * @var string
     *
     * @ORM\Column(name="payment_method", type="string", length=255, nullable=true)
     */
    protected $paymentMethod;

    /**
     * 有关支付的其他信息（一般来自第三方支付）
     * @var array
     *
     * @ORM\Column(name="payment_meta", type="array", nullable=true)
     */
    protected $paymentMeta;

    /**
     * @var float
     *
     * @ORM\Column(name="total_fee", type="float")
     */
    protected $totalFee = 0.0;

    /**
     * @var string
     *
     * @ORM\Column(name="trade_no", type="string", length=255, unique=true)
     */
    protected $tradeNo;

    /**
     * transaction id
     * @var string
     *
     * @ORM\Column(name="trans_id", type="string", length=255, nullable=true)
     */
    protected $transId;

    /**
     * 交易相关的应用数据，一是构造关联交易的内容，二是用来构造描述性摘要
     * @todo require data like user, fee, template etc
     * @var array
     *
     * @ORM\Column(name="content_data", type="array", nullable=true)
     */
    protected $contentData;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="summary", type="string", length=255, nullable=true)
     */
    protected $summary;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

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
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @param string $type
     * @return Trade
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     *
     * @param string $paymentMethod
     * @return Trade
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getPaymentMeta()
    {
        return $this->paymentMeta;
    }

    /**
     *
     * @param array $paymentMeta
     * @return Trade
     */
    public function setPaymentMeta($paymentMeta)
    {
        $this->paymentMeta = $paymentMeta;

        return $this;
    }

    /**
     * Set totalFee
     *
     * @param float $totalFee
     *
     * @return Trade
     */
    public function setTotalFee($totalFee)
    {
        $this->totalFee = $totalFee;

        return $this;
    }

    /**
     * Get totalFee
     *
     * @return float
     */
    public function getTotalFee()
    {
        return $this->totalFee;
    }

    /**
     * Set tradeNo
     *
     * @param string $tradeNo
     *
     * @return Trade
     */
    public function setTradeNo($tradeNo)
    {
        $this->tradeNo = $tradeNo;

        return $this;
    }

    /**
     * Get tradeNo
     *
     * @return string
     */
    public function getTradeNo()
    {
        return $this->tradeNo;
    }

    /**
     * Set transId
     *
     * @param string $transId
     *
     * @return Trade
     */
    public function setTransId($transId)
    {
        $this->transId = $transId;

        return $this;
    }

    /**
     * Get transId
     *
     * @return string
     */
    public function getTransId()
    {
        return $this->transId;
    }

    /**
     * Set contentData
     *
     * @param array $contentData
     *
     * @return Trade
     */
    public function setContentData($contentData)
    {
        $this->contentData = $contentData;

        return $this;
    }

    /**
     * Get contentData
     *
     * @return array
     */
    public function getContentData()
    {
        return $this->contentData;
    }

    /**
     * 支付时的商品名或者概要
     *
     * @param string $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     *
     * @return Trade
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Trade
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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

    public function isPaid()
    {
        return $this->state === self::STATE_PAID;
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
        $this->setState(self::STATE_TOBEPAID);

        return $this;
    }

    public function setStateApplicated()
    {
        $this->setState(self::STATE_APPLICATED);

        return $this;
    }

    public function setPayment(Payment $payment)
    {
        $this->setTotalFee($payment->getTotalFee())
            ->setPaymentMethod($payment->getMethod())
            ->setSummary($payment->getSummary());

        return $this;
    }
    
    public function __toString()
    {
        return $this->tradeNo ? $this->tradeNo : '新交易';
    }
}