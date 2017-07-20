<?php

namespace Lexing\MartBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Lexing\UserBundle\Entity\User;

class VehicleMartAdmin extends AbstractAdmin
{
    protected $translationDomain = 'LexingMartBundle';
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('addr')
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
            ->add('addr')
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
            ->with('基本信息', ['class' => 'col-md-8'])
                ->add('name')
                ->add('addr')
            ->end()
            ->with('管理人员', ['class' => 'col-md-4'])
                ->add('admins', 'sonata_type_model', [
                    'required' => false,
                    'label' => '账号',
                    'help' => '可添加多个',
                    'class' => User::class,
                    'by_reference' => false,
                    'multiple' => true,
                    'btn_add' => null,
                ])
            ->end()
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
            ->add('addr')
        ;
    }
}
