<?php

namespace Lexing\LoanBundle\Admin;

use Lexing\VehicleBundle\Entity\VehicleBrand;
use Lexing\VehicleBundle\Form\Type\VehicleModelFormType;
use Lexing\VehicleBundle\Entity\Vehicle;
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
use Nnv\FormBundle\Form\DataTransformer\EntityHiddenTransformer;
use Sonata\CoreBundle\Validator\ErrorElement;

/**
 * Class VehicleToRepayAdmin
 * @package Lexing\LoanBundle\Admin
 *
 * 【车辆还款】展示借款车辆并可操作还款
 */
class VehicleToRepayAdmin extends AbstractAdmin
{
    protected $translationDomain = 'LexingLoanBundle';

    protected $baseRouteName = 'admin_lexing_vehicle_vehicletorepay';

    protected $baseRoutePattern  = 'lexing/vehicle/to-repay';

    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        //'_sort_by' => 'vehicle.dealer'
    ];

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
            ->add('vehicle.dealer')
            ->add('vehicle', null, ['admin_code' => 'lexing_vehicle.admin.vehicle'])
            ->add('loanProduct')
            ->add('amountIn10K', null, ['sortable' => 'amount'])
            ->add('createdAt')
            ->add('loanRemarkIdentifier')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    // 'delete' => array(),
                    //'galleryset' => array('template' => 'LexingLoanBundle:Admin:list_action_gallery_set.html.twig'),
                    'repay' => array('template' => 'admin/list_action_repay.html.twig'),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $mortgage = $this->getSubject();
    
        $container = $this->getConfigurationPool()->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $vehicleId = $this->getRequest()->get('vehicle');
        $vehicle = null;
        if ($mortgage->getId()) {
            $vehicle = $mortgage->getVehicle();
        }
        if (!$vehicle && $vehicleId) {
            $vehicle = $em->getRepository('LexingVehicleBundle:Vehicle')->find($vehicleId);
        }
        $title = '车辆还款';
        $loanProduct = $mortgage->getLoanProduct();
        $description = $container->get('templating')->render('admin/mortgage_edit_description.html.twig', [
            'vehicle' => $vehicle,
            'mortgage' => $mortgage,
            'loanProduct' => $loanProduct,
            'isMortgage' => false,
            'isRepay' => true
        ]);
        $formMapper
            ->with($title,['description' => $description])
            ->add('vehicle', 'hidden', [
                'data' => $vehicle,
                'data_class' => null
            ], ['admin_code' => 'lexing_vehicle.admin.vehicle'])
            ->add('repaidAmount', null, ['required' => true])
            ->add('repaymentTransNo', null, ['required' => true]);
        $formMapper->get('vehicle')->addModelTransformer(new EntityHiddenTransformer(
            $em,
            Vehicle::class
        ));
    }
    
    public function validate(ErrorElement $errorElement, $object)
    {
        $form = $this->getForm();
        $repaidAmount = $form->get('repaidAmount')->getData();
        $loanAmount = $object->getAmount();
        if ($repaidAmount < $loanAmount) {
            $errorElement
                ->with('repaidAmount')
                ->addViolation('还款不能小于借款')
                ->end();
            return;
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
//        $container = $this->getConfigurationPool()->getContainer();
//        $em = $container->get('doctrine.orm.entity_manager');
//        // $em = $this->getModelManager()->getEntityManager($this->getClass());
//        $original = $em->getUnitOfWork()->getOriginalEntityData($object);
//        $oldState = $original['laonRemarkIdentifier'];
        
        $mortgage = $object;
        if ($mortgage->getRepaymentTransNo() && !$mortgage->getRepaidAt()) {
            $mortgage->setRepaidAt(new \DateTime());
            
            $dealer = $object->getVehicle()->getDealer();
            $dealer->changeUsedCredit(- $object->getAmount());
            //还款时自动下架
            $container = $this->getConfigurationPool()->getContainer();
            $em = $container->get('doctrine.orm.entity_manager');
            $em->transactional(function () use ($object, $dealer) {
                $vehicle = $object->getVehicle(); //自动下架
                $vehicle->setOnSale(false);
                $dealer->incNbOffSaleVehicles();
            });
        }
    }
    
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->andWhere($query->getRootAlias() . '.repaymentTransNo IS NULL');
        return $query;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('vehicle', null, ['admin_code' => 'lexing_vehicle.admin.vehicle'])
            ->add('loanProduct')
            ->add('amountIn10K', null, ['label' => '借款（万元）'])
            ->add('createdAt')
            //->add('info')
        ;
    }

    /**
     * @inheritdoc
     */
    public function getClassnameLabel()
    {
        return 'Vehicle To Repay';
    }

}
