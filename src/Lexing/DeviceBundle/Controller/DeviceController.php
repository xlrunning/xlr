<?php

namespace Lexing\DeviceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/device")
 */
class DeviceController extends Controller
{
    /**
     *
     * @Route("/list", name="lx_api_device_list")
     */
    public function listAction(Request $req)
    {
        return new Response('device list');
    }
}