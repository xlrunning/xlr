<?php

namespace Lexing\VehicleBundle\Controller;

use Lexing\VehicleBundle\Entity\Vehicle;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class VehicleAdminController extends Controller
{
    protected function redirectTo($object)
    {
        $url = null;
        $request = $this->getRequest();
        if (null !== $request->get('btn_onsale_tofalse') || null !== $request->get('btn_onsale_totrue')) {
            $url = $this->generateUrl('admin_lexing_vehicle_vehicle_edit', ['id'=>$object->getId(), 'action' => 'sale']);
            $this->get('session')
                ->getFlashBag()->set('sonata_flash_success', '操作成功');
        }
        if (null !== $request->get('btn_create_and_edit')) {
            $url = $this->generateUrl('admin_lexing_vehicle_vehicle_show', ['id'=>$object->getId()]);
            $this->get('session')
                ->getFlashBag()->set('sonata_flash_success', '添加成功');
        }

        if ($url) {
            return new RedirectResponse($url);
        }

        return parent::redirectTo($object);
    }

    /**
     * @Route("/admin/lexing/vehicle/vehicle/{id}/sale",
     *     defaults = { "_sonata_admin": "lexing_vehicle.admin.vehicle" },
     *     name = "admin_lexing_vehicle_vehicle_sale"
     * )
     */
    public function changeSaleStatusAction($id)
    {
        $em = $this->get('doctrine')->getManager();
        $vehicle = $em->getRepository('LexingVehicleBundle:Vehicle')->find($id);
        if (!$vehicle) {
            throw $this->createNotFoundException(
                'No vehicle found for id ' . $id
            );
        }
        $dealer = $vehicle->getDealer();

        $newStatus = !$vehicle->isOnSale();
        $vehicle->setOnSale($newStatus);
        if ($newStatus) {
            $string = '下架';
            $dealer->incNbOnSaleVehicles();
        } else {
            $string = '上架';
            $dealer->incNbOffSaleVehicles();
        }
        $em->flush();
        return new Response($string);
    }
}
