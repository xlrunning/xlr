<?php

namespace Nnv\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * 
 * @author Kail
 */
class ImageFileType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'image' => null,
            'maxw' => 400,
            'maxh' => 400,
            'compound' => false
        ));
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAttribute('image', $options['image'])
            ->setAttribute('maxw', $options['maxw'])
            ->setAttribute('maxh', $options['maxh'])
        ;
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['image'] = $options['image'];
        $view->vars['maxw'] = $options['maxw'];
        $view->vars['maxh'] = $options['maxh'];
    }
    
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'image_file';
    }
}
