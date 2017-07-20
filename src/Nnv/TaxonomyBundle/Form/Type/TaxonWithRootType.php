<?php

namespace Nnv\TaxonomyBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;

/**
 * TaxonWithRootType
 * 
 * @author Kail
 */
class TaxonWithRootType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $queryBuilder = function (Options $options) {
            if (isset($options['root'])) {
                $root = $options['root'];
                return function (EntityRepository $er) use ($root) {
                    return $er->createQueryBuilder('z')
                        ->where('z.root = :root')
                        //->andWhere('z.level = 0')
                        ->setParameter('root', $root);
                };
            }
        };
        
        $resolver->setDefaults(array(
            'level' => 0,
            'class' => 'Nnv\TaxonomyBundle\Entity\Taxon',
            'query_builder' => $queryBuilder
        ));
        
        $resolver->setRequired(array('root'))
                ->setAllowedTypes('level', array('integer'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'entity';
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['root'] = $options['root'];
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'taxon_with_root';
    }
}