<?php

namespace Lexing\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Lexing\VehicleBundle\DependencyInjection\Compiler\GlobalVariablesCompilerPass;

class LexingUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new GlobalVariablesCompilerPass());
    }
}
