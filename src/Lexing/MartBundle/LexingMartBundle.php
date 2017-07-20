<?php

namespace Lexing\MartBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Lexing\VehicleBundle\DependencyInjection\Compiler\GlobalVariablesCompilerPass;

class LexingMartBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new GlobalVariablesCompilerPass());
    }
}
