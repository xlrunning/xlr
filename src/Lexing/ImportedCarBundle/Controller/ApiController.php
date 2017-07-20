<?php

namespace Lexing\ImportedCarBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/imported")
 */
class ApiController extends Controller
{
    /**
     * @Route("/vehicle/store", name="lexing_imported_api_vehicle_store")
     *
     */
    public function vehicleStoreAction(Request $request)
    {
        // TODO hold on
    }

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
     * @Route("/brands", name="lexing_imported_api_brand")
     */
    public function brandsAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LexingImportedCarBundle:ImportedBrand');
        $brands = $repository->findAll();
        $data = [];
        foreach ($brands as $key => $brand) {
            $brandArr = [
                'name' => $brand->getName(),
                'brand_id' => $brand->getId(),
            ];
            $data[$brand->getId()][] = $brandArr;
        }
        return $this->json($data);

    }

    /**
     * Return Serie
     *
     * @Route("/series", name="lexing_imported_api_series")
     */
    public function seriesAction(Request $req)
    {
        $brandId = $req->get('brand');
        $repository = $this->getDoctrine()->getManager()->getRepository('LexingImportedCarBundle:ImportedSeries');
        $series = $repository->findBy(['brand' => $brandId]);
        $data = [];
        foreach ($series as $serie) {
            $serieArr = [
                'name' => $serie->getName(),
                'series_id' => $serie->getId(),
            ];
            $data[$serie->getName()][] = $serieArr;
        }
        return $this->json($data);
    }

    /**
     * Return Model
     *
     * @Route("/models", name="lexing_imported_api_model")
     */
    public function modelsAction(Request $req)
    {
        $serieId = $req->get('series');
        $repository = $this->getDoctrine()->getManager()->getRepository('LexingImportedCarBundle:ImportedModel');
        $models = $repository->findBy(['series' => $serieId]);
        $data = [];
        foreach ($models as $model) {
            $ModelArr = [
                'id' => $model->getId(),
                'name' => $model->getName(),
            ];
            $data[$model->getId()][] = $ModelArr;
        }
        return $this->json($data);
    }
}
