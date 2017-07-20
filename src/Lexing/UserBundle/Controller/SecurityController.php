<?php

namespace Lexing\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\SecurityController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SecurityController extends BaseController
{
    public function renderLogin(array $data)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            #return $this->redirect($this->generateUrl('jww_user_profile'));
        }
        
        $route = $this->get('request_stack')->getCurrentRequest()->get('_route');
        if ($route == 'admin_login') {
            $template = 'admin_login.html.twig';
        } else if ($route == 'mart_login') {
            $template = 'mart_login_2.html.twig';
        } else {
            $template = 'FOSUserBundle:Security:login.html.twig';
        }
        return $this->get('templating')->renderResponse($template, $data);
    }
    
    /**
     * 
     * @Route("/unauthorized", name="jww_unauthorized")
     * @Template()
     */
    public function unauthorizedAction(Request $req)
    {
        return [];
    }
}