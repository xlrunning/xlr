<?php

namespace Lexing\ImportedCarBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ImportedSeriesAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('name')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        // $brandChoices = [];

        //$em = $this->container->get('doctrine.orm.entity_manager');
        //$brands = $em->getRepository('LexingVehicleBundle:VehicleBrand')->findAll();
        //$brandIconPrefix = $this->container->getParameter('brand_icon_prefix');
        //$brandChoices = [];
        //$brandIds = [];
        //foreach ($brands as $brand) {
        //    $brandChoices[$brand->getInitial()][$brand->getName()] = $brand->getId();
        //    $brandIds[$brand->getId()] = $brand->getXinId();
        //}
        //$builder->addModelTransformer(new ModelToArrayTransformer($em));

        $formMapper
            //->add('id')
            ->add('name')
            ->add('brand')
            //->add('brand', 'choice', [
            //    'required' => false,
            //    'label' => '品牌',
            //    'placeholder' => '选择品牌',
            //    'attr' => ['data-prefix-img' => 'true'],
            //    'choices' => $brandChoices,
            //    'choice_attr' => function($key, $val, $index) use ($brandIds, $brandIconPrefix) {
            //        return ['data-img' => $brandIconPrefix . $brandIds[$key] . '.png'];
            //    }
            //])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
        ;
    }
}
