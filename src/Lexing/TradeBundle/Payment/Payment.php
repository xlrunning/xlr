<?php

namespace Lexing\TradeBundle\Payment;

/**
 * Class Payment
 * Plain object for providing necessary info to trade
 *
 * @package Lexing\TradeBundle\Payment
 */
class Payment
{
    /**
     * 分为单位
     * @var int
     */
    private $totalFee;

    /**
     * @var
     */
    private $method;

    /**
     * @var string
     */
    private $summary;

    public function __construct($totalFee, $method, $summary)
    {
        $this->totalFee = $totalFee;
        $this->method   = $method;
        $this->summary  = $summary;
    }

    /**
     * @return int
     */
    public function getTotalFee()
    {
        return $this->totalFee;
    }

    /**
     * @param int $totalFee
     * @return Payment
     */
    public function setTotalFee($totalFee)
    {
        $this->totalFee = $totalFee;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     * @return Payment
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     * @return Payment
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }
}