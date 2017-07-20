<?php

namespace Nnv\TradeBundle\Entity;

/**
 * TradeTrait
 */
trait TradeTrait
{
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

    // @todo 余额支付一部分，剩余第三方支付。支付方法不止一个

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
     * 交易相关的应用数据，一是用来构造描述性摘要，二是可以构造关联如充值、抽奖等依赖支付完成的对象
     * @todo require data like user, fee, template etc
     * @var array
     *
     * @ORM\Column(name="meta_data", type="json_array", nullable=true)
     */
    protected $metaData;

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
     * Set metaData
     *
     * @param array $metaData
     *
     * @return Trade
     */
    public function setMetaData($metaData)
    {
        $this->metaData = $metaData;

        return $this;
    }

    /**
     * Get metaData
     *
     * @return array
     */
    public function getMetaData()
    {
        return $this->metaData;
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
}