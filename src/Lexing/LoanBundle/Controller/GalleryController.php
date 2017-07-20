<?php

namespace Lexing\LoanBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Lexing\VehicleBundle\Entity\Vehicle;
use Nnv\GalleryBundle\Entity\Gallery;
use Nnv\GalleryBundle\Form\Type\GalleryType;

/**
 * Class GalleryController
 * @package Lexing\LoanBundle\Controller
 * @Route("/mortgage")
 */
class GalleryController extends Controller
{
    /**
     *
     *
     * @Route("/{id}/gallery/{code}", name="lx_vehicle_mortgage_gallery")
     * @Template()
     */
    public function indexAction($id, $code, Request $req)
    {
        $galleryConfig = $this->container->getParameter('lx.vehicle.galleries');
        $codes = array_keys($galleryConfig);
        if (!in_array($code, $codes)) {
            throw new \Exception('请先在lexing_vehicle配置会使用到的gallery code');
        }

        $em = $this->getDoctrine()->getManager();
        $mortgage = $em->getRepository('LexingLoanBundle:VehicleMortgage')->find($id);
        $galleries = $mortgage->getGalleries();

        $gallery = isset($galleries[$code]) ? $galleries[$code] : null;

        if (!$gallery) {
            $gallery = new Gallery();
            // @todo format string
            $gallery->setName('车辆借款的' . $galleryConfig[$code]['name'])
                ->setAbout('车辆借款')
                ->setCode($code)
                ->setAttached(true);
            $mortgage->addGallery($gallery);
            $em->persist($gallery);
            $em->flush();
        }

        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($req);

        if ($form->isValid()) {
            $em->persist($gallery);
            $em->flush();
            return $this->redirect($this->generateUrl('lx_vehicle_mortgage_gallery', ['id' => $id]));
        }

        return [
            'mortgage' => $mortgage,
            'gallery' => $gallery,
            'form' => $form->createView()
        ];
    }
    
    /**
     * Without VehicleMortgage entity
     *
     * @Route("/gallery/{code}", name="lx_vehicle_mortgage_gallery_standalone")
     * @Template()
     */
    public function standaloneAction($code, Request $req)
    {
        $galleryConfig = $this->container->getParameter('lx.vehicle.galleries');
        $codes = array_keys($galleryConfig);
        if (!in_array($code, $codes)) {
            throw new \Exception('请先在lexing_vehicle配置会使用到的gallery code');
        }
        
        $em = $this->getDoctrine()->getManager();
        $gallery = $em->getRepository('NnvGalleryBundle:Gallery')->findOneBy([
            'code' => $code,
            'attached' => false
        ]);
        
        if (!$gallery) {
            $gallery = new Gallery();
            // @todo format string
            $gallery->setName('车辆借款的' . $galleryConfig[$code]['name'])
                ->setAbout('车辆借款')
                ->setCode($code);
            // $vehicle->addGallery($gallery);
            $em->persist($gallery);
            $em->flush();
        }
        
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($req);
        
        if ($form->isValid()) {
            $em->persist($gallery);
            $em->flush();
            return $this->redirect($this->generateUrl('lx_vehicle_mortgage_gallery_standalone'));
        }
        
        return [
            'gallery' => $gallery,
            'form' => $form->createView()
        ];
    }
}