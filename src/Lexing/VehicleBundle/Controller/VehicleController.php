<?php

namespace Lexing\VehicleBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Nnv\FormBundle\Form\Type\KeyValType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/vehicle")
 */
class VehicleController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $vehicle = new \stdClass();
        $vehicle->vin   = '123450AB';
        $vehicle->info  = [];
        $vehicle->kv  = ['key'=>'车系名称', 'val'=>'宝马M3 2009款 M3双门轿跑车'];

        $form = $this->createFormBuilder($vehicle)
            ->add('vin')
            ->add('info', CollectionType::class, [
                'entry_type' => KeyValType::class,
                'allow_add' => true,
                'allow_delete' => true
            ])
            //->add('kv', PairType::class)
            ->add('save', SubmitType::class, array('label' => '提交'))
            ->getForm();

        return $this->render('LexingVehicleBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/data/brand")
     */
    public function dataBrandAction()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('品牌', 'brand');

        $em = $this->getDoctrine()->getManager();
        $query = $em->createNativeQuery('SELECT * FROM zhijia_brand_2017_02_03', $rsm);

        $result = $query->getResult();
        dump($result);
        exit;
    }
}
