<?php

namespace Lexing\LoanBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class VehicleMortgageAdminController extends Controller
{
    protected function redirectTo($object)
    {
        $url = null;
        $request = $this->getRequest();
        if (null !== $request->get('btn_repay')) {
            $url = 'http://baidu.com';
        }
        if ($url) {
            // return new RedirectResponse($url);
        }

        return parent::redirectTo($object);
    }
}
