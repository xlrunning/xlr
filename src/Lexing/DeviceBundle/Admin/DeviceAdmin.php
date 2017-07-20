<?php

namespace Lexing\DeviceBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class DeviceAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('type')
            ->add('uuid')
            ->add('secret')
            ->add('expiredAt')
            ->add('requestIp')
            ->add('createdAt')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('type')
            ->add('uuid')
            #->add('secret')
            ->add('expiredAt')
            ->add('requestIp')
            ->add('createdAt')
            #->add('updatedAt')
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
            ->add('user') // 仅仅是dealer的人？
            ->add('type', 'choice', ['choices' => ['旺POS' => 'wpos', 'iPhone' => 'iphone', '安卓' => 'android']])
            ->add('uuid', null, ['help' => '旺POS为mcode-en-sn'])
            ->add('secret')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('type')
            ->add('uuid')
            ->add('secret')
            ->add('expiredAt')
            ->add('requestIp')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
}
