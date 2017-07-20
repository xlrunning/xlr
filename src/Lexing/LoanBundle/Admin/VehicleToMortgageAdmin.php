<?php

namespace Lexing\LoanBundle\Admin;

use Lexing\VehicleBundle\Entity\VehicleBrand;
use Lexing\VehicleBundle\Form\Type\VehicleModelFormType;
use Nnv\FormBundle\Form\Type\KeyValType;
use Nnv\FormBundle\Form\Type\LinkedChoiceType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Sonata\DoctrineORMAdminBundle\Filter\CallbackFilter;

/**
 * Class VehicleToMortgageAdmin
 * @package Lexing\LoanBundle\Admin
 *
 * 【车辆借款】展示可抵押车辆列表和借款操作
 */
class VehicleToMortgageAdmin extends AbstractAdmin
{
    protected $translationDomain = 'LexingVehicleBundle';

    protected $baseRouteName = 'admin_lexing_vehicle_vehicletomortgage';

    protected $baseRoutePattern  = 'lexing/vehicle/to-mortgage';

    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'dealer.id'
    ];

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('dealer')
            // sonata admin似乎不支持Embeddable
            ->add('modelBrand', CallbackFilter::class, array(
                'callback' => array($this, 'getModelBrandFilter'),
                'field_type' => 'text',
                'show_filter'=> true
            ))
            ->add('onSale')
            ->add('plateLoc')
            ->add('plateNo')
            ->add('vin')
            ->add('mileage')
            ->add('age')
            ->add('color')
        ;
    }

    public function getModelBrandFilter($queryBuilder, $alias, $field, $value)
    {
        if (!$value['value']) {
            return;
        }

        // Use `andWhere` instead of `where` to prevent overriding existing `where` conditions
        $queryBuilder->andWhere($queryBuilder->expr()->like($alias.'.model.brand', $queryBuilder->expr()->literal('%' . $value['value'] . '%')));
        return true;
    }
    
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('delete')
            //->remove('edit')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
//            ->add('plateLoc')
//            ->add('plateNo')
            ->add('dealer')
            ->add('vin')
            ->add('model.brand')
            ->add('model')
            ->add('mileage')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    // 'delete' => array(),
                    'contractgalleryset' => array('template' => 'LexingVehicleBundle:Admin:list_action_contract_gallery_set.html.twig'),
                    //'galleryset' => array('template' => 'LexingVehicleBundle:Admin:list_action_gallery_set.html.twig'),
                    'mortgage' => array('template' => 'admin/list_action_mortgage.html.twig'),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->andWhere($query->getRootAlias() . '.onSale = :onSale')
            ->andWhere($query->getRootAlias() . '.mortgage IS NULL')
            ->setParameter('onSale', true);
        return $query;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('dealer')
            ->add('plateLoc')
            ->add('plateNo')
            ->add('soucheModel')
            ->add('vin')
            ->add('model')
            ->add('mileage')
            ->add('age')
            ->add('color')
            //->add('info')
            ->add('galleries', null, ['template' => 'admin/vehicle_show_contract.html.twig', 'label' => '合同图片'])
        ;
    }

    /**
     * @inheritdoc
     */
    public function getClassnameLabel()
    {
        return 'Vehicle To Mortgage';
    }
}
