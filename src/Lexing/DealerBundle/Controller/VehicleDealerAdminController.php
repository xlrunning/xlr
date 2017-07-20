<?php

namespace Lexing\DealerBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class VehicleDealerAdminController extends Controller
{
    protected function redirectTo($object)
    {
        $url = null;
        $request = $this->getRequest();
        if (null !== $request->get('btn_credit')) {
            $url = $this->generateUrl('admin_lexing_dealer_vehicledealer_list');
            $this->get('session')
                ->getFlashBag()->set('sonata_flash_success', '授信成功');
        }
        if ($url) {
             return new RedirectResponse($url);
        }

        return parent::redirectTo($object);
    }
}