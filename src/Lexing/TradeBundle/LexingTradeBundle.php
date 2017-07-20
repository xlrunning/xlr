<?php

namespace Lexing\TradeBundle;

use Lexing\TradeBundle\DependencyInjection\Compiler\RegisterHandlersCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LexingTradeBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterHandlersCompilerPass());
    }
}
