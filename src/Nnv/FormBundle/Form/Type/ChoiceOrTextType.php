<?php

namespace Nnv\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Nnv\FormBundle\Form\DataTransformer\ValueToChoiceOrTextTransformer;

class ChoiceOrTextType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('choice', 'choice', [
                'choices' => $options['choices'] + ['other' => '其他'],
                'required' => false,
            ])
            ->add('text', 'text', [
                'required' => false,
            ])
            ->addModelTransformer(new ValueToChoiceOrTextTransformer($options['choices']))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('choices'))
                ->setAllowedTypes(array('choices' => 'array'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'choice_or_text';
    }    
}