<?php

namespace Lexing\XinBundle\Controller;

use Lexing\VehicleBundle\Entity\VehicleBrand;
use Lexing\VehicleBundle\Entity\VehicleModel;
use Lexing\VehicleBundle\Entity\VehicleSerie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/xin/data")
 */
class DataController extends Controller
{
    /**
     * 导入优信的品牌数据
     * @Route("/brand")
     */
    public function brandAction()
    {
        return new Response('brands done, exe cmd to import serie and models!');
    }
}
