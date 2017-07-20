<?php

namespace Lexing\UserBundle\Admin;

use Lexing\UserBundle\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class UserAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'id'
    ];

    protected $translationDomain = 'LexingUserBundle';
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('email')
            ->add('nick')
            ->add('mobile')
            ->add('name')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('username')
            ->add('email')
            ->add('lastLogin')
            ->add('userRoleType', 'choice', ['choices' => User::getUserRoleTypes()])
            ->add('mobile')
            ->add('name')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    public function preUpdate($object)
    {
        parent::preUpdate($object);

        if (!empty($object->getPlainPassword())) {
            $um = $this->getConfigurationPool()->getContainer()->get('fos_user.user_manager');
            // $um->updateCanonicalFields($object);
            $um->updatePassword($object);
        }
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        $targetUser = $this->getSubject();
        if ($targetUser !== null) {
            $targetUser->setEnabled(true);
        }
        $container = $this->getConfigurationPool()->getContainer();
        $isSuperAdmin = $container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN');
//
//        $dpOptions1 = [
//            'label' => '生日',
//            'format' => 'yyyy-MM-dd', // 只能是提供的有限几种之一
//            'dp_language' => 'zh_CN',
//            'data' => $targetUser->getBirth() ? $targetUser->getBirth() : new \DateTime(),
//            'datepicker_use_button' => false,
//            'dp_side_by_side' => true,
//            'required' => false
//        ];
        $formMapper
            ->with('基本信息', ['tab' => true])
            ->with('登陆信息', ['class' => 'col-md-4 pull-right'])
            ->add('username', null, array('label' => '登录名', 'required' => true, 'attr' => array('placeholder' => '英文数字')))
            ->add('email', null, array('label' => '邮箱', /*'read_only' => $targetUser->getId() != null,*/ 'attr' => []))
            ->add('plainPassword', 'text', array('required' => false, 'label' => '密码', 'attr' => []))
            ->end()
            ->with('用户信息', ['class' => 'col-md-8 pull-left'])
            //->add('nick', null, ['label' => '微信昵称'])
            //->add('avatar', ImageLinkType::class, ['label' => '微信头像', 'required' => false])
            //->add('openid', null, ['label' => '微信openid'])
            ->add('name', null, array('label' => '姓名', 'required' => false))
            ->add('mobile', null, array('label' => '手机号', 'required' => false))
            // ->add('gender', 'choice', array('label' => '性别', 'choices' => ['女', '男'], 'required' => false))
            ->end()
            ->end();
        if ($isSuperAdmin) {
            $formMapper
                ->with('扩展信息', ['tab' => true])
                ->with('基础管理')
                ->add('adminDealer', null, ['label' => '关联车商', 'help' => '关联车商后就可以以该车商身份登入APP'])
                ->add('enabled', null, array('required' => false, 'data' => true, 'label' => '激活'))
                ->add('userRoleType', 'choice', ['label' => '用户类型', 'required' => false, 'choices' => array_flip(User::getUserRoleTypes())])
                ->add('superAdmin', 'checkbox', array('required' => false, 'label' => '超级管理员', 'data' => $targetUser->hasRole('ROLE_SUPER_ADMIN')))
                ->end()
                ->end();
        }
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('username')
            ->add('usernameCanonical')
            ->add('email')
            ->add('emailCanonical')
            ->add('enabled')
            ->add('salt')
            ->add('password')
            ->add('lastLogin')
            ->add('confirmationToken')
            ->add('passwordRequestedAt')
            ->add('roles')
            ->add('balance')
            ->add('id')
            ->add('openid')
            ->add('nick')
            ->add('mobile')
            ->add('avatar')
            ->add('name')
        ;
    }
}
