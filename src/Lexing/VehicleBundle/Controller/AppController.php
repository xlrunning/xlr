<?php

namespace Lexing\VehicleBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AppController
 * @package Lexing\VehicleBundle\Controller
 * @Route("/app")
 */
class AppController extends Controller
{

    /**
     * @Route("/detail/{id}", name="app_vehicle_detail")
     * @Template("app/detail/vehicle_detail.html.twig")
     */
    public function detailAction($id)
    {
        $dealer = null;
        if ($this->isGranted('ROLE_USER')) {
            $user = $this->getUser();
            // POS机还是手机
            $dealer = $user->getAdminDealer();
            // $user->getAdminDealer();
        }
        $vehicleRep = $this->getDoctrine()->getManager()->getRepository('LexingVehicleBundle:Vehicle');
        $vehicle = $vehicleRep->find($id);
        return [
            'vehicle' => $vehicle,
            'dealer' => $dealer
        ];
    }

    /**
     * @Route("/index",name="app_index")
     * @Template("app/list/list_page.html.twig")
     */
    public function ListAction(Request $request)
    {
        $dealer = null;
        if ($this->isGranted('ROLE_USER')) {
            $user = $this->getUser();
            // POS机还是手机
//            $dealer = $user->getWposDealer();
            $dealer = $user->getAdminDealer();
        }

        $page = $request->get('page') > 0 ? $request->get('page') : 1;
        $itemNum = 10;
        $numItemsPerPage = ($page - 1) * $itemNum;
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT v, s FROM LexingVehicleBundle:Vehicle v LEFT JOIN v.sales s';
        $params = [];
        if ($dealer) {
            $dql .= ' WHERE v.dealer = :dealer';
            $params['dealer'] = $dealer;
        }

        //品牌查询
        $brand = $request->get('brand');
        if ($brand && $dealer){
            $dql .= ' AND v.model.brand = :brand';
            $params['brand'] = $brand;
        }

        $vehicleArr = $em->createQuery($dql)
            ->setParameters($params)
            ->setMaxResults($itemNum)
            ->setFirstResult($numItemsPerPage)
            ->getResult();

        $vehicleRes = $em->createQuery($dql)
            ->setParameters($params)
            ->getResult();

        $vehicleNum = count($vehicleRes);


        $brandArr = $this->get('lexing_vehicle.get_vehicle_info')->getBrand();
        return $this->render($page>1 ? 'app/list/list_items.html.twig' : 'app/list/list_page.html.twig', [
                'vehicleArr' => $vehicleArr,
                'page' => $page,
                'brandArr' => $brandArr,
                'vehicleNum' => $vehicleNum
            ]
        );

    }

    /**
     * 条件查询
     * @Route("/query",name="app_query")
     * @Template("")
     */
    public function queryAction(Request $request)
    {

    }

    /**
     * @Route("/test")
     */
    public function testAction()
    {
        dump($this->get('lexing_vehicle.get_vehicle_info')->getBrand());
        return new Response(123);
    }


}
