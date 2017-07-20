<?php

namespace Lexing\VehicleBundle\Admin;

use Lexing\VehicleBundle\Entity\VehicleBrand;
use Lexing\VehicleBundle\Form\Type\VehicleColorFormType;
use Lexing\VehicleBundle\Form\Type\VehicleModelFormType;
use Nnv\FormBundle\Form\Type\KeyValType;
use Nnv\FormBundle\Form\Type\LinkedChoiceType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\CallbackFilter;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class VehicleAdmin extends AbstractAdmin
{
    protected $translationDomain = 'LexingVehicleBundle';

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
            ->add('dealer', null, array('show_filter' => true) )
            ->add('onSale', null, array('show_filter' => true) )
            // sonata admin似乎不支持Embeddable
            ->add('modelBrand', CallbackFilter::class, array(
                'callback' => array($this, 'getModelBrandFilter'),
                'field_type' => 'text'
            ))
            ->add('plateLoc')
            ->add('plateNo')
            ->add('vin')
            ->add('mileage')
            ->add('age')
            ->add('color')
        ;
    }

    //  在售筛选默认值设置为是。
    public function getFilterParameters()
    {
        $this->datagridValues = array_merge(array(
            'onSale' => array(
                'value' => true,
            )
        ),
            $this->datagridValues

        );
        return parent::getFilterParameters();
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
            ->add('galleries', null , ['template' => 'admin/list_vehicle_gallery.html.twig'])
            ->add('mileage')
            ->add('evaluation')
            ->add('updatedAt')
            ->add('onSale', null, ['template' => 'LexingVehicleBundle:Admin:list_is_on_sale.html.twig'])
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'sale' => array('template' => 'admin/list_action_vehicle_sale.html.twig'),
                    // 'delete' => array(),
                    'galleryset' => array('template' => 'LexingVehicleBundle:Admin:list_action_gallery_set.html.twig'),
                )
            ))
        ;
    }

    private function setGalleryIfDetected($vehicle)
    {
        $galleries = $vehicle->getGalleries();
        if (isset($galleries['vehicle.content'])) {
            return;
        }

        $galleryId = $this->getRequest()->request->getInt('gallery');
        if (!$galleryId) {
            return;
        }
        $container = $this->getConfigurationPool()->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $gallery = $em->getRepository('NnvGalleryBundle:Gallery')
            ->find($galleryId);
        $vehicle->addGallery($gallery);
    }
    
    private function setContractIfDetected($vehicle)
    {
        $galleries = $vehicle->getGalleries();
        if (isset($galleries['mortgage.contract']) || isset($galleries['lease.contract']) || isset($galleries['sales.contract'])) {
            return;
        }
        
        $galleryId = $this->getRequest()->request->getInt('gallery');
        if (!$galleryId) {
            return;
        }
        $container = $this->getConfigurationPool()->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $gallery = $em->getRepository('NnvGalleryBundle:Gallery')
            ->find($galleryId);
        $vehicle->addGallery($gallery);
    }
    
    public function prePersist($object)
    {
        $this->setGalleryIfDetected($object);
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        $this->setGalleryIfDetected($object);
        $this->setContractIfDetected($object);

        $container = $this->getConfigurationPool()->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $original = $em->getUnitOfWork()->getOriginalEntityData($object);
        $oldOnSale = $original['onSale'];

        $dealer = $object->getDealer();
        if ($oldOnSale && !$object->isOnSale()) {
            $object->setOffSaleAt(new \DateTime());
            $object->setOffSaleNote('人工下架，请查看操作记录');
        }
    
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $vehicle = $this->getSubject();
        $action = $this->getRequest()->get('action');
        $isContractSet = $action == "contractSet";
        $container = $this->getConfigurationPool()->getContainer();
        $description = $container->get('templating')->render('admin/vehicle_edit_description.html.twig', [
            'vehicle' => $vehicle,
            'isContractSet' => $isContractSet
        ]);
        
        if ($action == 'sale') {
            $formMapper->with('基础信息', ['description' => $description]);
            $formMapper->add('onSale', 'hidden', ['data' => $vehicle->isOnSale() ? 0 : 1]);
            return;
        }

        $vehicleKeys = $container->getParameter('vehicle_keys');
        $plateLocArr = $container->getParameter('plateLoc');
        $colorArr = $container->getParameter('colors');
        if(!$isContractSet){
            $formMapper
                ->with('基础信息', ['class' => 'col-md-8', 'description' => $description])
                    ->add('dealer')
                    ->add('plateLoc','choice',['choices'=>array_combine($plateLocArr,$plateLocArr)])
                    ->add('plateNo')
                    ->add('vin', null, ['attr' => ['maxlength' => 17, 'minlength' => 17]])
                    ->add('soucheModel')
                    ->add('assocModel', VehicleModelFormType::class, [])
                    ->add('mileage', null ,['label' => '行驶里程（公里）'])
                    ->add('evaluation', null ,['label' => '车辆估价（万元）'])
                    ->add('age', null ,['label' => '车龄（年）'])
                    ->add('color', VehicleColorFormType::class, [])
                ->end()
                ->with('其他信息', ['class' => 'col-md-4'])
                    ->add('info', 'sonata_type_native_collection', [
                        'entry_type' => KeyValType::class,
                        'entry_options' => [
                            'key_type' => 'choice',
                            'key_choices' => array_combine($vehicleKeys, $vehicleKeys)
                        ],
                        'allow_add' => true,
                        'allow_delete' => true,
                    ])
                ->end()
            ;
            $formMapper->get('info')->addModelTransformer(new CallbackTransformer(
                // render
                function ($info) {
                    if (is_array($info)) {
                    }
                    return $info;
                },
                // save
                function ($info) {
                    return array_values($info);
                }
            ));
        }
        if($isContractSet){
            $formMapper
                ->with('基础信息', ['class' => 'col-md-8', 'description' => $description]);
        }
        
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
            ->add('galleries', null, ['template' => 'admin/vehicle_show_galleries.html.twig', 'label' => '车辆图片'])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function generateUrl($name, array $parameters = array(), $absolute = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        if ($name == 'list' && $this->isCurrentRoute('edit') && ($this->getRequest()->get('action') == 'contractSet')) {
            return $this->getRouteGenerator()->generate('admin_lexing_vehicle_vehicletomortgage_list');
        }
        return parent::generateUrl($name, $parameters, $absolute);
    }
    
    public function getClassnameLabel()
    {
        if ($this->isCurrentRoute('edit') && ($this->getRequest()->get('action') == 'contractSet')) {
            return 'Vehicle To Mortgage';
        }
        return parent::getClassnameLabel();
    }
}
