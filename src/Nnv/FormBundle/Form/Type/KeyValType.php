<?php

namespace Nnv\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * KeyValType
 *
 * 键值对
 * 
 * @author Kail
 */
class KeyValType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $keyType = $options['key_type'];
        $keyOptions = ['label' => $options['key_label']];
        if ('choice' == $keyType) {
            $keyOptions['choices'] = $options['key_choices'];
        }
        $builder
            ->add('key', $keyType, $keyOptions)
            ->add('val', null, ['label' => $options['val_label'], 'data' => $options['val_default']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'compound' => true,
            'key_type' => 'text',
            'key_label' => '名称',
            'val_label' => '值',
            'val_default' => ''
        ));
        $resolver
            ->setAllowedValues('key_type', ['text', 'choice'])
            ->setDefined('key_choices');
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'key_val';
    }
}