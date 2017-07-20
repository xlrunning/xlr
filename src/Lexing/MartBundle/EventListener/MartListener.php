<?php

namespace Lexing\MartBundle\EventListener;

use Lexing\StationBundle\MartAware;
use Lexing\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class MartListener
 */
class MartListener
{
    use ContainerAwareTrait;

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        if (!is_array($controller)) {
            return;
        }

        if (!($controller[0] instanceof MartAware)) {
            return;
        }
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if (is_a($user, User::class)) {
            $mart = $user->getAdminMart();
            $this->container->get('twig')->addGlobal('_mart', $mart);
        }
    }
}