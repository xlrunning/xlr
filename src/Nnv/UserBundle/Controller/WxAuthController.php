<?php

namespace Nnv\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * 微信自动登入和注册
 * 
 * @todo config: enabled or not, register or not, redirect url etc
 * 
 * @Route("/wxauth")
 */
class WxAuthController extends Controller
{
    /**
     * 微信自动登入
     * 
     * @Route("/login", name="nnv_wxauth_login")
     */
    public function loginAction(Request $req)
    {
        $wxhelper = $this->get('nnv_wx.helper');
        if ($wxhelper->isInWeChat()) {
            $authUrl = $wxhelper->getAuthUrl($this->generateUrl('nnv_wxauth_return', [], UrlGeneratorInterface::ABSOLUTE_URL), 'base');
            return $this->redirect($authUrl);
        }
        return $this->redirect($this->generateUrl('fos_user_security_login'));
    }
    
    /**
     * 用于登陆的微信授权
     * 
     * @Route("return", name="nnv_wxauth_return")
     */
    public function returnAction(Request $req)
    {
        $code = $req->get('code');
        if (empty($code)) {
            die('wx auth failed');
        }
        
        $wxhelper = $this->get('nnv_wx.helper');
        $ret = $wxhelper->getUserInfo($code, 'base');
        if ($ret === false) {
            die('noauth');
            // 用户没有授权
            // return $this->redirectToRoute('sofashion_index');
        }
        
        $openid = $ret;
        if (empty($openid)) {
            $logger = $this->get('logger');
            $logger->error('openid is null');
            return $this->redirectToRoute('fos_user_security_login');
        }
        
        $em = $this->getDoctrine()->getManager();
        $resolvedTo = $this->container->getParameter('nnv.resolved_to');
        $userEntityClass = $resolvedTo['Nnv\\UserBundle\\Entity\\User'];
        $user = $em->getRepository($userEntityClass)
                ->findOneBy(['openid' => $openid]);
        if (empty($user)) {
            // 去微信授权获取昵称头像然后再注册
            return $this->redirectToRoute('nnv_wxauth_anon_register_redirect');
        }
        
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
        $url = $req->getSession()->get('_security.main.target_path');
        
        return $this->redirect($url);
    }
    
    /**
     * 用于自动注册的微信授权跳转
     * 
     * @Route("/anon-register-redirect", name="nnv_wxauth_anon_register_redirect")
     */
    public function wxauthAnonRegisterRedirectAction(Request $req)
    {
        $wxhelper = $this->get('nnv_wx.helper');
        if ($wxhelper->isInWeChat()) {
            $authUrl = $wxhelper->getAuthUrl($this->generateUrl('nnv_wxauth_anon_register', [], UrlGeneratorInterface::ABSOLUTE_URL), 'userinfo');
            return $this->redirect($authUrl);
        }
        return $this->redirect($this->generateUrl('fos_user_registration_register'));
    }
    
    /**
     * 用于注册的微信授权，无表单
     * 
     * @Route("/anon-register", name="nnv_wxauth_anon_register")
     */
    public function wxauthAnonRegisterAction(Request $req)
    {
        $code = $req->get('code');
        if (empty($code)) {
            die('wx auth failed');
        }
        
        $wxhelper = $this->get('nnv_wx.helper');
        $userinfo = $wxhelper->getUserInfo($code, 'userinfo');
        if ($userinfo === false) {
            // 用户没有授权
            // return $this->redirectToRoute('sofashion_index');
            die('noauth');
        }
        
        $openid = $userinfo['openid'];
        if (empty($openid)) {
            $logger = $this->get('logger');
            $logger->error('openid is null');
            return $this->redirectToRoute('fos_user_security_login');
        }
        
        $em = $this->getDoctrine()->getManager();
        $resolvedTo = $this->container->getParameter('nnv.resolved_to');
        $userEntityClass = $resolvedTo['Nnv\\UserBundle\\Entity\\User'];
        $user = $em->getRepository($userEntityClass)->findOneBy(['openid' => $openid]);
        if (!empty($user)) {
            die('user already exists');
            // return $this->redirectToRoute('sofashion_index');
        }
        // 确认用户确实不存在，直接创建用户
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        // @todo username and email 默认自动生成逻辑
        $username = uniqid('nnv_');
        $email = $username . '@nnv.io';
        $user->setOpenid($openid)
             ->setNick($userinfo['nick'])
             ->setAvatar(isset($userinfo["avatar"]) ? $userinfo["avatar"] : null)
             ->setUsername($username)
             ->setEmail($email)
             ->setEnabled(true)
             ->setPlainPassword($username . time());
        $userManager->updatePassword($user);
        
        // @todo dispatch event to make affiliate possible
        
        $em->persist($user);
        $em->flush();
        
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
        $url = $req->getSession()->get('_security.main.target_path');
        
        return $this->redirect($url);
    }
}
