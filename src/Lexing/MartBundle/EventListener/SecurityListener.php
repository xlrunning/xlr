<?php

namespace Lexing\MartBundle\EventListener;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class SecurityListener
 */
class SecurityListener
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $req = $event->getRequest();
        if (!$req->attributes->get('mart_admin_login', false)) {
            return;
        }
        $user = $event->getAuthenticationToken()->getUser();
        $station = $user->getAdminStation();
        if (!$station) {
            throw new AccessDeniedException('你的账号没有和授权点关联，请联系管理员');
        }
    }
}