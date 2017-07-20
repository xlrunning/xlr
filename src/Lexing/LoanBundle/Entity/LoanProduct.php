<?php

namespace Lexing\LoanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * LoanProduct
 * 贷款产品
 *
 * @ORM\Table(name="loan_product")
 * @ORM\Entity(repositoryClass="Lexing\LoanBundle\Repository\LoanProductRepository")
 */
class LoanProduct
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 定期还是随借随还
     * @var bool
     *
     * @ORM\Column(name="fixed", type="boolean")
     */
    private $fixed = true;

    /**
     * 期限
     * 如果是随借随还，这个时间就是最长借款时间
     * @var int
     *
     * @ORM\Column(name="period_by_month", type="integer")
     */
    private $periodByMonth;

    /**
     * 随借随还的起步时间
     * @var int
     *
     * @ORM\Column(name="non_fixed_starting_days", type="integer")
     */
    private $nonFixedStartingDays = 7;

    /**
     * 年化利率
     * 随借随还按天（interestPercentageByYear/360）计算，定期按月interestPercentageByYear/12
     * @var float
     *
     * @ORM\Column(name="interest_percentage_by_year", type="float")
     */
    private $interestPercentageByYear;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fixed
     *
     * @param boolean $fixed
     *
     * @return LoanProduct
     */
    public function setFixed($fixed)
    {
        $this->fixed = $fixed;

        return $this;
    }

    /**
     * Get fixed
     *
     * @return bool
     */
    public function getFixed()
    {
        return $this->fixed;
    }

    /**
     * Set periodByMonth
     *
     * @param integer $periodByMonth
     *
     * @return LoanProduct
     */
    public function setPeriodByMonth($periodByMonth)
    {
        $this->periodByMonth = $periodByMonth;

        return $this;
    }

    /**
     * Get periodByMonth
     *
     * @return int
     */
    public function getPeriodByMonth()
    {
        return $this->periodByMonth;
    }

    /**
     * @return int
     */
    public function getNonFixedStartingDays()
    {
        return $this->nonFixedStartingDays;
    }

    /**
     * @param int $nonFixedStartingDays
     * @return LoanProduct
     */
    public function setNonFixedStartingDays($nonFixedStartingDays)
    {
        $this->nonFixedStartingDays = $nonFixedStartingDays;
        return $this;
    }

    /**
     * Set interestPercentageByYear
     *
     * @param float $interestPercentageByYear
     *
     * @return LoanProduct
     */
    public function setInterestPercentageByYear($interestPercentageByYear)
    {
        $this->interestPercentageByYear = $interestPercentageByYear;

        return $this;
    }

    /**
     * Get interestPercentageByYear
     *
     * @return float
     */
    public function getInterestPercentageByYear()
    {
        return $this->interestPercentageByYear;
    }

    public function __toString()
    {
        $fixed = $this->fixed == 1 ? '定期' : '随借随还';
        if ($this->fixed) {
            $title = "定期 - {$this->periodByMonth}月 - 年{$this->interestPercentageByYear}%";
        } else {
            $title = "随借随还 - {$this->nonFixedStartingDays}天起借 - 最长{$this->periodByMonth}个月 - 年{$this->interestPercentageByYear}%";
        }
        return $this->id ? $title : '新贷款产品';
    }
}

