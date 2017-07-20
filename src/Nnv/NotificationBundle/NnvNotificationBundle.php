<?php

namespace Nnv\NotificationBundle;

use Nnv\NotificationBundle\DependencyInjection\Compiler\RegisterProvidersPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Nnv\NotificationBundle\DependencyInjection\Compiler\RegisterListenersPass;

class NnvNotificationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterProvidersPass());
        $container->addCompilerPass(new RegisterListenersPass());
    }
}
