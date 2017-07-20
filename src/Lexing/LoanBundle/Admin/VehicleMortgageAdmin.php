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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class VehicleMortgageAdmin
 * @package Lexing\LoanBundle\Admin
 *
 * 车辆还款历史
 */
class VehicleMortgageAdmin extends AbstractAdmin
{
    protected $translationDomain = 'LexingLoanBundle';

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
            ->add('amount')
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

    public function validate(ErrorElement $errorElement, $object)
    {
        $form = $this->getForm();
        $amount = $form->get('amountIn10K')->getData();
        $getAvailableCredit = $object->getVehicle()->getDealer()->getAvailableCreditIn10K();
        if ($amount > $getAvailableCredit) {
            $errorElement
                ->with('amountIn10K')
                ->addViolation('超过借款额度')
                ->end();
            return;
        }
    }
 
    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        $amount = 10000*$object->getAmountIn10K();
        $object->setAmount($amount);

        $mortgageAmount = $object->getAmount();
        if (isset($mortgageAmount)) {
            $dealer = $object->getVehicle()->getDealer();
            $dealer->changeUsedCredit($mortgageAmount);
        }

        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $loanRemarkIdentifier = '';
        for ($i = 0; $i < 6; ++$i)  {
            $loanRemarkIdentifier .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        $object->setLoanRemarkIdentifier($loanRemarkIdentifier);
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
        $title = '车辆借款';
        if ($mortgage->isRepaid()) {
            $title = '车辆借还款';
        }
        $loanProduct = $mortgage->getLoanProduct();
        $description = $container->get('templating')->render('admin/mortgage_edit_description.html.twig', [
            'vehicle' => $vehicle,
            'mortgage' => $mortgage,
            'loanProduct' => $loanProduct,
            'isMortgage' => true,
            'isRepay' => false
        ]);
        $vehicleEvaluation = 20000;
        $mortgageAmountAdvice = 0.6*$vehicleEvaluation/10000;
        $formMapper
            ->with($title,['description' => $description])
            ->add('vehicle', 'hidden', [
                'data' => $vehicle,
                'data_class' => null
            ], ['admin_code' => 'lexing_vehicle.admin.vehicle'])
            ->ifTrue(!$mortgage->getId())
                ->add('loanProduct', null, ['required' => true, 'placeholder' => '请选择贷款产品'])
                ->add('amountIn10K', 'text', ['label' => '贷款金额（万元）', 'attr' => ['value' => $mortgageAmountAdvice ]])
            ->ifEnd();
        $formMapper->get('vehicle')->addModelTransformer(new EntityHiddenTransformer(
            $em,
            Vehicle::class
        ));
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        return;
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

    // 贷款历史不应该有"添加"
    public function getDashboardActions()
    {
        $actions = parent::getDashboardActions();
        unset($actions['create']);
        return $actions;
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
            ->add('amount')
            ->add('createdAt')
            ->add('repaymentTransNo')
            ->add('repaidAmount')
            ->add('repaidAt')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function generateUrl($name, array $parameters = array(), $absolute = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        if ($name == 'list' && $this->isCurrentRoute('create')) {
            return $this->getRouteGenerator()->generate('admin_lexing_vehicle_vehicletomortgage_list');
        }
        return parent::generateUrl($name, $parameters, $absolute);
    }
    
    public function getClassnameLabel()
    {
        if ($this->isCurrentRoute('create')) {
            return 'Vehicle To Mortgage';
        }
        return parent::getClassnameLabel();
    }
}
