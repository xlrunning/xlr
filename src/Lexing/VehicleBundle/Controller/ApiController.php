<?php

namespace Lexing\VehicleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiController
 *
 * @Route("/api/vehicle")
 * @package Lexing\VehicleBundle\Controller
 */
class ApiController extends Controller
{
    /**
     * @override
     */
    protected function json($data, $status = 200, $headers = array(), $context = array())
    {
        return parent::json([
            'code' => 1,
            'message' => '操作成功',
            'version' => 0.1,
            'data'    => $data
        ], $status, $headers, $context);
    }

    /**
     * Return brand
     *
     * @Route("/brands", name="lx_api_vehicle_brands")
     */
    public function brandsAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LexingVehicleBundle:VehicleBrand');
        $brands = $repository->findAll();
        $data = [];
        $imgPrefix = $this->container->getParameter('brand_icon_prefix');
        foreach ($brands as $key => $brand) {
            $brandArr = [
                'name' => $brand->getName(),
                'urlImg' => $imgPrefix . $brand->getXinId() . '.png',
                'brandId' => $brand->getId(),
            ];
            $data[$brand->getInitial()][] = $brandArr;
        }
        return $this->json($data);

    }

    /**
     * Return Serie
     *
     * @Route("/series", name="lx_api_vehicle_series")
     */
    public function seriesAction(Request $req)
    {
        $brandId = $req->get('brand');
        $repository = $this->getDoctrine()->getManager()->getRepository('LexingVehicleBundle:VehicleSerie');
        $series = $repository->findBy(['brand' => $brandId]);
        $data = [];
        foreach ($series as $serie) {
            $serieArr = [
                'name' => $serie->getName(),
                'makeName' => $serie->getMakeName(),
                'serieId' => $serie->getId(),
            ];
            $data[$serie->getMakeName()][] = $serieArr;
        }
        return $this->json($data);
    }


    /**
     * Return Model
     *
     * @Route("/models", name="lx_api_vehicle_models")
     */
    public function modelsAction(Request $req)
    {
        $serieId = $req->get('serie');
        $repository = $this->getDoctrine()->getManager()->getRepository('LexingVehicleBundle:VehicleModel');
        $models = $repository->findBy(['serie' => $serieId]);
        $data = [];
        foreach ($models as $model) {
            $ModelArr = [
                'id' => $model->getId(),
                'name' => $model->getName(),
                "year" => $model->getYear(),
            ];
            $data[$model->getYear()][] = $ModelArr;
        }
        return $this->json($data);
    }
}
