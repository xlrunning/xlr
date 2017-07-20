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
class DocFileType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'doc' => null,
            'doc_path' => null,
            'doc_type' => null,
            'doc_size' => null,
            'compound' => false
        ));
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAttribute('doc', $options['doc'])
            ->setAttribute('doc_path', $options['doc_path'])
            ->setAttribute('doc_size', $options['doc_size'])
        ;
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['doc'] = $options['doc'];
        $view->vars['doc_path'] = $options['doc_path'];
        $view->vars['doc_size'] = $options['doc_size'];
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
        return 'doc_file';
    }
}
