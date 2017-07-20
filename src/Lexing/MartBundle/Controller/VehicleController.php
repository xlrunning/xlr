<?php

namespace Lexing\MartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class VehicleController
 * @Route("/mart-admin")
 * @package Lexing\MartBundle\Controller
 */

class VehicleController extends Controller
{
    /**
     * @Route("/vehicle/list",name="mart_admin_vehicle_list")
     */
    public function vehicleListAction()
    {
        return $this->render('LexingMartBundle:Vehicle:vehicle_list.html.twig', array(

        ));
    }


    /**
     * @Route("/vehicle/add",name="mart_admin_vehicle_add")
     */
    public function vehicleAddAction()
    {
        return $this->render('LexingMartBundle:Vehicle:vehicle_add.html.twig', array(

        ));
    }

}
