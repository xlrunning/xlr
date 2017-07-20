<?php

namespace Lexing\LoanBundle\Admin;

use Lexing\LoanBundle\Entity\CreditApplication;
use Lexing\LoanBundle\Entity\CreditExtension;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Nnv\FormBundle\Form\Type\KeyValType;
use Sonata\CoreBundle\Validator\ErrorElement;

class CreditApplicationAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('initial')
            ->add('state')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('dealer')
            ->add('initial')
            ->add('state')
            ->add('createdAt')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $quota = $this->getForm()->get('quota')->getData();
        if ($object->isApproved() && empty($quota)) {
            $errorElement
                ->with('quota')
                ->addViolation('请选择一个额度')
                ->end();
            return;
        }
    }

    public function preUpdate($object)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        // $em = $this->getModelManager()->getEntityManager($this->getClass());
        $original = $em->getUnitOfWork()->getOriginalEntityData($object);
        $oldState = $original['state'];
        if ($object->isApproved() && $oldState != CreditApplication::STATE_APPROVED) {
            $creditExtension = new CreditExtension();
            $quota = $this->getForm()->get('quota')->getData();
            $creditExtension->setDealer($object->getDealer())
                ->setQuota($quota);
            $container = $this->getConfigurationPool()->getContainer();
            $em = $container->get('doctrine.orm.entity_manager');
            $em->persist($creditExtension);
        }
    }

    public function prePersist($object)
    {

    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        // @todo 一旦审批通过就不可以再修改

        $quotasYuan = range(200000, 1000000, 100000);
        $quotasW = array_map(function($val){
            return $val/10000 . '万';
        }, $quotasYuan);
        $creditApp = $this->getSubject();
        if ($creditApp->isApproved()) {
            // 显示授信情况
        }
        $formMapper
            //->add('initial')
            //->add('state')
            ->add('dealer')
            ->add('state', 'choice', ['label' => '状态', 'choices' => [
                '批准' => CreditApplication::STATE_APPROVED,
                '审批中' => CreditApplication::STATE_UNDERWAY,
                '拒绝' => CreditApplication::STATE_REJECTED,
            ]])
            ->add('quota', 'choice', [
                'label' => '额度', 'mapped' => false, 'help' => '仅当批准为该车商创建授信时起效',
                'required' => false,
                'choices' => array_combine($quotasW, $quotasYuan),
            ])
            ->add('steps', 'sonata_type_native_collection', [
                'label' => '进度',
                'entry_type' => KeyValType::class,
                'entry_options' => [
                    'key_type' => 'text',
                    'key_label' => '步骤说明',
                    'val_label' => '时间等',
                    'val_default' => date('Y-m-d H:i:s')
                ],
                'help' => '目前主要做用演示用，未来使用工作流实现',
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('dealer')
            ->add('initial')
            ->add('state')
            ->add('steps')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
}
