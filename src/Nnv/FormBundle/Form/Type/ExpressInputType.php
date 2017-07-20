<?php

namespace Nnv\FormBundle\Form\Type;

use Nnv\FormBundle\Form\Type\ChoiceInputType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * 选择输入快递公司
 * 
 * @author Kail
 */
class ExpressInputType extends ChoiceInputType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $expressCorps = [
            '顺丰快递',
            '申通快递',
            '圆通快递',
            '中通快递',
            '天天快递',
            '韵达快递'
        ];

        $resolver->setDefaults(array(
            'choices' => $expressCorps
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice_input';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'express_input';
    }
}
