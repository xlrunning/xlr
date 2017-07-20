<?php

namespace Lexing\MartBundle\Controller;

use Lexing\MartBundle\Entity\VehicleMart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class DealersController
 * @Route("/mart-admin")
 * @package Lexing\MartBundle\Controller
 */
class DealerController extends Controller
{
    /**
     * @Route("/dealer/list",name="mart_admin_dealer_list")
     */
    public function dealerListAction()
    {
        $rep = $this->getDoctrine()->getManager()->getRepository('LexingMartBundle:VehicleMart');
        $marts = $rep->findAll();

        return $this->render('LexingMartBundle:Dealers:dealer_list.html.twig', array(
            "_mart"=>'能成汽车城',
            "datas"=>$marts
        ));
    }

    /**
     * @Route("/dealer/add",name="mart_admin_dealer_add")
     */
    public function dealerAddAction()
    {
        $mart = new VehicleMart();
        $form = $this->createFormBuilder($mart)
            ->add('name','text',['label'=>'车商姓名'])
            ->add("addr",'text',['label'=>'车商地址'])
            ->add("save",'submit',['label'=>'添加车商'])
            ->getForm();

        return $this->render('LexingMartBundle:Dealers:dealer_add.html.twig',array(
            'form'=> $form->createView(),
        ));
    }

}
