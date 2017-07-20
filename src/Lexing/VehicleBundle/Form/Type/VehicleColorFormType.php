<?php

namespace Lexing\VehicleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Lexing\VehicleBundle\Form\DataTransformer\ArrayToStringTransformer;

class VehicleColorFormType extends AbstractType
{
    private static $colorMapping = [
        '白' => '#fff',
        '黑' => '#0f0f0f',
        '灰' => '#aaa',
        '蓝' => '#38adf0',
        '红' => '#ff3e3e',
        '黄' => '#f3e72a',
        '橙' => '#ffa200',
        '绿' => '#8def6f',
        '紫' => '#f16ff3',
        '多彩色' => 'linear-gradient(45deg, #f71c1c 10%, #f76b1c 35%, #486ab3 54%, #39be49 70%, #fbda61 68%)',
        '银灰色' => '#d9d9d9',
        '香槟色' => '#ddcb85',
        '粉红色' => '#ef408a',
        '咖啡色' => '#a07272',
        '其他' => 'linear-gradient(45deg, #fff 49%, #e5e5e5 25%)',
    ];

    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $colors = $this->container->getParameter('colors');
        $builder->addModelTransformer(new ArrayToStringTransformer($em));
        $builder
            ->add('color', ChoiceType::class, [
                'required' => false,
                'label' => ' ',
                'placeholder' => '选择颜色',
                'attr' => ['data-prefix-colorspan' => 'true'],
                'choices' =>  array_combine($colors, $colors),
                'choice_attr' => function($val, $key, $index) {
                    return ['data-colorspan' => self::$colorMapping[$key]];
                },
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'vehicle_color';
    }
}