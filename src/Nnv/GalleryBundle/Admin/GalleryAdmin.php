<?php

namespace Nnv\GalleryBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class GalleryAdmin extends AbstractAdmin
{
    protected $translationDomain = 'NnvGalleryBundle';
    
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt'
    ];
    
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('code')
            ->add('nbItems')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('code')
            ->add('name')
            ->add('nbItems')
            ->add('createdAt', null, ['format' => 'Y-m-d H:i'])
            ->add('_action', null, array(
                'actions' => array(
                    #'show' => array(),
                    'edit' => array(),
                    'manage' => array('template' => 'NnvGalleryBundle:Admin:list_action_manage_gallery.html.twig'),
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
            ->add('name')
            ->add('code', null, ['help' => '通常用于提示APP用在何处，如homepage, guide, onboarding的英文数字类字符串'])
            ->add('about', 'textarea', [
                'help' => '将会显示在管理页面头部作为说明、指示信息',
                'attr' => ['rows' => 8],
                'required' => false
            ])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('about')
            ->add('code')
            ->add('name')
            ->add('nbItems')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
}
