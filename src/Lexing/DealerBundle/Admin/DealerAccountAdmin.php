<?php

namespace Lexing\DealerBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class DealerAccountAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('bankName')
            ->add('accountName')
            ->add('accountNumber')
            ->add('accountMobile')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('dealer')
            ->add('bankName')
            ->add('accountName')
            ->add('accountNumber')
            ->add('accountMobile')
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
        $formMapper
            ->add('dealer')
            ->add('bankName')
            ->add('accountName')
            ->add('accountNumber')
            ->add('accountMobile')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('dealer')
            ->add('bankName')
            ->add('accountName')
            ->add('accountNumber')
            ->add('accountMobile')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
}
