<?php

namespace Nnv\FormBundle;

use Nnv\FormBundle\DependencyInjection\Compiler\ChoicePopulatorPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NnvFormBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ChoicePopulatorPass());
    }
}
