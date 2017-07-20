<?php

namespace Lexing\TradeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/trade")
     */
    public function indexAction()
    {
        return $this->render('LexingTradeBundle:Default:index.html.twig');
    }
}
