<?php

namespace Lexing\LoanBundle\Service;

use Lexing\LoanBundle\Entity\LoanProduct;

/**
 * class RepaymentCalculator
 */
class RepaymentCalculator
{

    /**
     *
     * @param $capitalYuan
     * @param $periodByMonth
     * @param $interestPercentageByYear
     * @return float
     */
    public function calcFixedInterest($capitalYuan, $periodByMonth, $interestPercentageByYear)
    {
        $interestYuan = $capitalYuan * $periodByMonth * ($interestPercentageByYear / 1200);
        return ceil($interestYuan);
    }

    /**
     *
     * @param $capitalYuan
     * @param $days
     * @param $interestPercentageByYear
     * @return float
     */
    public function calcNonFixedInterest($capitalYuan, $days, $interestPercentageByYear)
    {
        $interestYuan = $capitalYuan * $days * ($interestPercentageByYear / 36000);
        return ceil($interestYuan);
    }
}