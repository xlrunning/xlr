<?php

namespace Nnv\TaxonomyBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;


class TaxonAdmin extends AbstractAdmin
{
    
    protected $translationDomain = 'NnvTaxonomyBundle';
    
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt'
    ];
       
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper             
            ->add('title')
            ->add('level') 
            ->add('parent');
    }
    
    
    
    private function preSave($object)
    {
//        $em = $this->modelManager->getEntityManager('JsnewQuestionBundle:Question');
        
    }
    
    public function prePersist($object)
    {
        $this->preSave($object);
    }
    
    public function preUpdate($object)
    {
        $this->preSave($object);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title', null, ['label' => '标题'])
            ->add('level', null, ['label' => '级别'])
            ->add('parent', null, ['label' => '父类'])
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
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {       
        $showMapper
            ->with('详情')
                ->add('title')
                ->add('level') 
                ->add('parent')
            ->end()
        ;
    }
}
