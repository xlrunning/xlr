<?php

namespace Lexing\ImportedCarBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ImportedVehicleAdmin extends AbstractAdmin
{
    protected $translationDomain = 'LexingImportedCarBundle';

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('color')
            ->add('info')
            ->add('pic')
            ->add('cover')
            ->add('onSale')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('color')
            ->add('info')
            //->add('pic')
            //->add('cover')
            ->add('onSale')
            //->add('createdAt')
            //->add('updatedAt')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    // 'delete' => array(),
                    //'galleryset' => array('template' => 'LexingVehicleBundle:Admin:list_action_gallery_set.html.twig'),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            // ->add('id')
            ->add('color')
            ->add('info')
            ->add('pic')
            ->add('cover')
            ->add('onSale')
            //->add('createdAt')
            //->add('updatedAt')

        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('color')
            ->add('info')
            ->add('pic')
            ->add('cover')
            ->add('onSale')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
}
