<?php

namespace Nnv\WxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * 
 * @Route("/nnv/wx/")
 */
class WxController extends Controller
{
    /**
     * 
     * @Route("/update")
     */
    public function updateAction(Request $req)
    {
        
    }
}