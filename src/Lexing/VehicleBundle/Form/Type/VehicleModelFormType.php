<?php

namespace Lexing\VehicleBundle\Form\Type;

use Lexing\VehicleBundle\Form\DataTransformer\ModelToArrayTransformer;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Nnv\FormBundle\Form\Type\LinkedChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * VehicleModelFormType
 *
 */
class VehicleModelFormType extends AbstractType
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $brands = $em->getRepository('LexingVehicleBundle:VehicleBrand')->findAll();
        $brandIconPrefix = $this->container->getParameter('brand_icon_prefix');
        $brandChoices = [];
        $brandIds = [];
        foreach ($brands as $brand) {
            $brandChoices[$brand->getInitial()][$brand->getName()] = $brand->getId();
            $brandIds[$brand->getId()] = $brand->getXinId();
        }
        $builder->addModelTransformer(new ModelToArrayTransformer($em));
        $builder
            ->add('brand', 'choice', [
                'required' => false,
                'label' => '品牌',
                'placeholder' => '选择品牌',
                'attr' => ['data-prefix-img' => 'true'],
                'choices' => $brandChoices,
                'choice_attr' => function($key, $val, $index) use ($brandIds, $brandIconPrefix) {
                    return ['data-img' => $brandIconPrefix . $brandIds[$key] . '.png'];
                }
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $assocModel = $event->getData();
            $form = $event->getForm();
            $serieChoices = null;
            $modelChoices = null;
            if ($assocModel) {
                $serie = $assocModel->getSerie();
                $brand = $serie->getBrand();
                $populator = $this->container->get('lexing_vehicle.choice_populator');
                $serieChoices = $populator->populateBrandSeries($brand->getId());
                $modelChoices = $populator->populateSerieModels($serie->getId());
            }
            $form->add('serie', LinkedChoiceType::class, [
                'label' => '车系',
                'placeholder' => '请先选择品牌',
                'attr' => ['data-prefix-optgroup' => 'true'],
                'choices' => $serieChoices,
                'populate_from' => 'brand'
            ])->add('model', LinkedChoiceType::class, [
                'label' => '车型',
                'placeholder' => '请先选择车系',
                'choices' => $modelChoices,
                'attr' => ['data-prefix-optgroup' => 'true'],
                'populate_from' => 'serie'
            ]);
            // addViewTransformer to avoid integer(id) to be transformed to string
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'compound' => true
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        //$view->vars['multiple'] = false;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'vehicle_model';
    }
}