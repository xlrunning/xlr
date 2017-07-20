<?php

namespace Lexing\DealerBundle\Admin;

use Lexing\DealerBundle\Entity\VehicleDealer;
use Nnv\FormBundle\Form\Type\KeyValType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class VehicleDealerAdmin extends AbstractAdmin
{
    protected $translationDomain = 'LexingDealerBundle';

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
            ->add('name')
            ->add('shortName')
            ->add('mart')
            ->add('customerNo')
            ->add('siteNo')
            ->add('squarePrice')
            ->add('rented')
            ->add('deposit');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('shortName')
            ->add('mart')
//            ->add('customerNo')
//            ->add('siteNo')
//            ->add('squarePrice')
//            ->add('rented')
//            ->add('deposit')
            ->add('creditExtensionAmountIn10K', null, ['sortable' => 'creditExtensionAmount'])
            ->add('nbTotalVehicles')
            ->add('nbOnSaleVehicles')
            ->add('nbOffSaleVehicles')
            ->add('usedCreditIn10K', null, ['sortable' => 'usedCredit'])
            ->add('availableCreditIn10K')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                    'credit' => array('template' => 'admin/list_action_credit.html.twig'),
                )
            ));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $dealer = $this->getSubject();
        $container = $this->getConfigurationPool()->getContainer();
        $action = $this->getRequest()->get('action');
        if ($action == 'credit') {
            $isCredit = $action == 'credit';
            $description = $container->get('templating')->render('admin/dealer_edit_description.html.twig', [
                'dealer' => $dealer,
                'isCredit' => $isCredit
            ]);
            $formMapper->with('基础信息', ['description' => $description]);
            $formMapper->add('creditExtensionAmountIn10K', 'text', ['label' => '授信金额（万元）']);
            return;
        }


        $formMapper
            ->with('基础信息', ['class' => 'col-md-8'])
                ->add('name')
                ->add('shortName')
                ->add('mart')
//                ->add('customerNo')
//                ->add('siteNo')
//                ->add('squarePrice')
//                ->add('rented')
//                ->add('deposit')
            ->end()
            ->with('联系方式', ['class' => 'col-md-4'])
                ->add('contact_phone', 'sonata_type_native_collection', [
                'entry_type' => KeyValType::class,
                'entry_options' => [
                    'key_type' => 'text',
                    'key_label' => '姓名',
                    'val_label' => '手机'
                ],
                'allow_add' => true,
                'allow_delete' => true,
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
            ->add('name')
            ->add('shortName')
            ->add('mart')
            ->add('customerNo')
            ->add('siteNo')
            ->add('squarePrice')
            ->add('rented')
            ->add('deposit');
    }

    public function toString($object)
    {
        return $object instanceof VehicleDealer ? $object->getName() : 'Vehicle';
    }
}
