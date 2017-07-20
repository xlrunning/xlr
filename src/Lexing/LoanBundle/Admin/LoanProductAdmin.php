<?php

namespace Lexing\LoanBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class LoanProductAdmin extends AbstractAdmin
{
    protected $translationDomain = 'LexingLoanBundle';

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('fixed')
            ->add('periodByMonth')
            ->add('nonFixedStartingDays')
            ->add('interestPercentageByYear')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('fixed')
            ->add('periodByMonth')
            ->add('nonFixedStartingDays')
            ->add('interestPercentageByYear')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('fixed', null, ['help' => '定期／随借随还'])
            ->add('periodByMonth', null, ['help' => '定期：借款时长／随借随还：最长借款时间'])
            ->add('nonFixedStartingDays')
            ->add('interestPercentageByYear')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('fixed')
            ->add('periodByMonth')
            ->add('nonFixedStartingDays')
            ->add('interestPercentageByYear')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
}
