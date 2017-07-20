<?php

namespace Lexing\LoanBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/loan")
 */
class LoanController extends Controller
{
    /**
     * @Route("/req")
     */
    public function indexAction()
    {
        echo $this->get('lexing_loan.repayment_calculator')->calcFixedInterest(1000, 3, 12);
        exit;
        return $this->render('LexingLoanBundle:Default:index.html.twig');
    }
}
