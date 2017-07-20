<?php

namespace Nnv\WxBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
#use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class WxidUserProvider implements UserProviderInterface
{
    protected $em;
    protected $container;
    protected $userCls;
    
    protected $users = array();
    
    public function __construct($em, $container)
    {
        $this->em = $em;
        $this->container = $container;
        $this->userCls = $this->container->getParameter('fos_user.model.user.class');
    }
    
    public function getUsernameForWxid($wxid)
    {
        $user = $this->em->getRepository($this->userCls)
                ->findOneBy(array('openid' => $wxid));
        // Look up the username based on the token in the database, via
        // an API call, or do something entirely different
        
        if ($user) {
            // 与登录时处理相同，DRY it
            $username = $user->getUsername();
            $this->users[$username] = $user;
            return $username;
        }
        return null;
    }

    public function loadUserByUsername($username)
    {
        if (isset($users[$username])) {
            return $users[$username];
        }
        
        $user = $this->em->getRepository($this->userCls)
                ->findOneBy(array('username' => $username));
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        // $user is the User that you set in the token inside authenticateToken()
        // after it has been deserialized from the session
        $id = $user->getId();
        $user = $this->em->getRepository($this->userCls)
                ->find($id);
        
        return $user;
    }

    public function supportsClass($class)
    {
        return $this->userCls === $class;
    }
}