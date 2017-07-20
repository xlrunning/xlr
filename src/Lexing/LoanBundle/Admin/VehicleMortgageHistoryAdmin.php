<?php

namespace Lexing\LoanBundle\Admin;

use Lexing\LoanBundle\Entity\LoanProduct;
use Lexing\VehicleBundle\Entity\Vehicle;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Nnv\FormBundle\Form\DataTransformer\EntityHiddenTransformer;

/**
 * Class VehicleMortgageHistoryAdmin
 * @package Lexing\LoanBundle\Admin
 *
 * 车辆还款历史
 */
class VehicleMortgageHistoryAdmin extends AbstractAdmin
{
    protected $translationDomain = 'LexingLoanBundle';

    protected $baseRouteName = 'admin_lexing_vehicle_vehiclemortgagehistory';

    protected $baseRoutePattern  = 'lexing/vehicle/mortgage-history';

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('vehicle.dealer')
            ->add('amount')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('vehicle.dealer')
            ->add('vehicle', null, ['admin_code' => 'lexing_vehicle.admin.vehicle'])
            ->add('amountIn10K', null, ['sortable' => 'amount'])
            ->add('createdAt')
            ->add('loanRemarkIdentifier')
            ->add('repaymentTransNo')
            ->add('repaidAmount')
            ->add('repaidAt')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    //'edit' => array(),
                    //'delete' => array(),
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

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('delete')
            ->remove('edit')
        ;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->andWhere($query->getRootAlias() . '.repaymentTransNo IS NOT NULL');
        return $query;
    }


    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('vehicle.dealer')
            ->add('vehicle', null, ['admin_code' => 'lexing_vehicle.admin.vehicle'])
            ->add('amountIn10K')
            ->add('createdAt')
            ->add('repaymentTransNo')
            ->add('repaidAmount')
            ->add('repaidAt')
        ;
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);

        return $actions;
    }

    /**
     * @inheritdoc
     */
    public function getClassnameLabel()
    {
        return 'Vehicle Mortgage History';
    }

}
