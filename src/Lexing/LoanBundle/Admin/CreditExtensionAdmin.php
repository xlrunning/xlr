<?php

namespace Lexing\LoanBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CreditExtensionAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('dealer')
            ->add('quota')
            ->add('usedAmount')
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
            ->add('quota')
            ->add('usedAmount')
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
        $quotasYuan = range(200000, 1000000, 100000);
        $quotasW = array_map(function($val){
            return $val/10000 . '万';
        }, $quotasYuan);
        $formMapper
            ->add('dealer')
            ->add('quota', 'choice', [
                'choices' => array_combine($quotasW, $quotasYuan)
            ])
            //->add('usedAmount')
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
            ->add('quota')
            ->add('usedAmount')
        ;
    }
}
