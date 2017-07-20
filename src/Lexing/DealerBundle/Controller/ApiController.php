<?php

namespace Lexing\DealerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Lexing\DeviceBundle\Entity\DealerWPos;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/api/dealer")
 */
class ApiController extends Controller
{
    /**
     * 车商信息
     * @Route("/info", name="lx_api_dealer_info", defaults={"_format": "json"})
     */
    public function infoAction(Request $req)
    {
        if (!$this->isGranted('ROLE_USER') || (!$dealer = $this->getUser()->getAdminDealer())) {
            throw new AccessDeniedHttpException('无关联车商');
        }

        // @todo navigation menu etc?
        // @todo 有没有消息，贷款批准与否，统计信息（在库车辆数，抵押车辆数等等）
        return $this->json([
            'ok' => 1,
            'id' => $dealer->getId(),
            'name' => $dealer->getName(),
            'mart' => $dealer->getMart()->getName(),
        ]);
    }
}