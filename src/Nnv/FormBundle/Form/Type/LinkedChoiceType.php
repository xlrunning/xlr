<?php

namespace Nnv\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;

/**
 * LinkedChoiceType
 * parent不是choice，因为validation很麻烦，但是template用到了choice的，详细查看bundle下fields.html.twig
 *
 * @author Kail
 */
class LinkedChoiceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['populate_from'])
            ->setDefined(['populate_to', 'placeholder']);
        $resolver->setDefaults(array(
            'compound' => false,
            'placeholder' => '',
            'choices' => null
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // we are using choice widget underhood, so some variables need set
        $view->vars['preferred_choices'] = null;
        $view->vars['choices'] = $options['choices'];
        $view->vars['placeholder'] = $options['placeholder'];

        $view->vars['populate_from'] = $options['populate_from'];
        $view->vars['populate_to']   = isset($options['populate_to']) ? $options['populate_to'] : $view->vars['name'];
        $view->vars['population_endpoint'] = 'nnv_form_populate_choices';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'linked_choice';
    }
}
