<?php

namespace Nnv\GalleryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Nnv\GalleryBundle\Form\Type\GalleryItemType;
use Nnv\GalleryBundle\Entity\Gallery;

class GalleryType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => Gallery::class
        ));
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('items', 'collection', [
                'label'        => '图片库',
                'entry_type'   => GalleryItemType::class,
                'by_reference' => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true
            ])
        ;
    }
    
    public function getBlockPrefix()
    {
        return 'nnv_form_gallery';
    }
}