<?php

namespace Lexing\VehicleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Lexing\VehicleBundle\Entity\Vehicle;
use Nnv\GalleryBundle\Entity\Gallery;
use Nnv\GalleryBundle\Form\Type\GalleryType;

/**
 * Class GalleryController
 * @package Lexing\VehicleBundle\Controller
 * @Route("/vehicle")
 */
class GalleryController extends Controller
{
    /**
     *
     *
     * @Route("/{id}/gallery/{code}", name="lx_vehicle_gallery")
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
        $vehicle = $em->getRepository('LexingVehicleBundle:Vehicle')->find($id);
        $galleries = $vehicle->getGalleries();
        
        $gallery = isset($galleries[$code]) ? $galleries[$code] : null;
        
        if (!$gallery) {
            $gallery = new Gallery();
            // @todo format string
            $gallery->setName('车辆的' . $galleryConfig[$code]['name'])
                ->setAbout('车辆')
                ->setCode($code)
                ->setAttached(true);
            $vehicle->addGallery($gallery);
            $em->persist($gallery);
            $em->flush();
        }
        
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($req);
        
        if ($form->isValid()) {
            $em->persist($gallery);
            $em->flush();
            return $this->redirect($this->generateUrl('lx_vehicle_gallery', ['id' => $id]));
        }
        
        return [
            'vehicle' => $vehicle,
            'gallery' => $gallery,
            'form' => $form->createView()
        ];
    }
    
    /**
     * Without Vehicle entity
     *
     * @Route("/gallery/{code}", name="lx_vehicle_gallery_standalone")
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
            $gallery->setName('车辆的' . $galleryConfig[$code]['name'])
                ->setAbout('车辆')
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
            return $this->redirect($this->generateUrl('lx_vehicle_gallery_standalone'));
        }
        
        return [
            'gallery' => $gallery,
            'form' => $form->createView()
        ];
    }
    
    /**
     *
     *
     * @Route("/{id}/mortgagecontract/gallery/{code}", name="lx_vehicle_mortgagecontract_gallery")
     * @Template()
     */
    public function mortgageContractAction($id, $code, Request $req)
    {
        $galleryConfig = $this->container->getParameter('lx.vehicle.galleries');
        $codes = array_keys($galleryConfig);
        if (!in_array($code, $codes)) {
            throw new \Exception('请先在lexing_vehicle配置会使用到的gallery code');
        }
    
        $em = $this->getDoctrine()->getManager();
        $vehicle = $em->getRepository('LexingVehicleBundle:Vehicle')->find($id);
        $galleries = $vehicle->getGalleries();
    
        $gallery = isset($galleries[$code]) ? $galleries[$code] : null;
    
        if (!$gallery) {
            $gallery = new Gallery();
            // @todo format string
            $gallery->setName('车辆的' . $galleryConfig[$code]['name'])
                ->setAbout('车辆抵押合同')
                ->setCode($code)
                ->setAttached(true);
            $vehicle->addGallery($gallery);
            $em->persist($gallery);
            $em->flush();
        }
        
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($req);
        
        if ($form->isValid()) {
            $em->persist($gallery);
            $em->flush();
            return $this->redirect($this->generateUrl('lx_vehicle_mortgagecontract_gallery', ['id' => $id]));
        }
        
        return [
            'vehicle' => $vehicle,
            'gallery' => $gallery,
            'form' => $form->createView()
        ];
    }
    
    /**
     *
     *
     * @Route("/{id}/leasecontract/gallery/{code}", name="lx_vehicle_leasecontract_gallery")
     * @Template()
     */
    public function leaseContractAction($id, $code, Request $req)
    {
        $galleryConfig = $this->container->getParameter('lx.vehicle.galleries');
        $codes = array_keys($galleryConfig);
        if (!in_array($code, $codes)) {
            throw new \Exception('请先在lexing_vehicle配置会使用到的gallery code');
        }
    
        $em = $this->getDoctrine()->getManager();
        $vehicle = $em->getRepository('LexingVehicleBundle:Vehicle')->find($id);
        $galleries = $vehicle->getGalleries();
    
        $gallery = isset($galleries[$code]) ? $galleries[$code] : null;
    
        if (!$gallery) {
            $gallery = new Gallery();
            // @todo format string
            $gallery->setName('车辆的' . $galleryConfig[$code]['name'])
                ->setAbout('车辆租赁合同')
                ->setCode($code)
                ->setAttached(true);
            $vehicle->addGallery($gallery);
            $em->persist($gallery);
            $em->flush();
        }
        
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($req);
        
        if ($form->isValid()) {
            $em->persist($gallery);
            $em->flush();
            return $this->redirect($this->generateUrl('lx_vehicle_leasecontract_gallery', ['id' => $id]));
        }
        
        return [
            'vehicle' => $vehicle,
            'gallery' => $gallery,
            'form' => $form->createView()
        ];
    }
    
    /**
     *
     *
     * @Route("/{id}/salescontract/gallery/{code}", name="lx_vehicle_salescontract_gallery")
     * @Template()
     */
    public function salesContractAction($id, $code, Request $req)
    {
        $galleryConfig = $this->container->getParameter('lx.vehicle.galleries');
        $codes = array_keys($galleryConfig);
        if (!in_array($code, $codes)) {
            throw new \Exception('请先在lexing_vehicle配置会使用到的gallery code');
        }
    
        $em = $this->getDoctrine()->getManager();
        $vehicle = $em->getRepository('LexingVehicleBundle:Vehicle')->find($id);
        $galleries = $vehicle->getGalleries();
    
        $gallery = isset($galleries[$code]) ? $galleries[$code] : null;
    
        if (!$gallery) {
            $gallery = new Gallery();
            // @todo format string
            $gallery->setName('车辆的' . $galleryConfig[$code]['name'])
                ->setAbout('车辆买卖合同')
                ->setCode($code)
                ->setAttached(true);
            $vehicle->addGallery($gallery);
            $em->persist($gallery);
            $em->flush();
        }
        
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($req);
        
        if ($form->isValid()) {
            $em->persist($gallery);
            $em->flush();
            return $this->redirect($this->generateUrl('lx_vehicle_salescontract_gallery', ['id' => $id]));
        }
        
        return [
            'vehicle' => $vehicle,
            'gallery' => $gallery,
            'form' => $form->createView()
        ];
    }
 
}
