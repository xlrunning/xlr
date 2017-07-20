<?php

namespace Lexing\TradeBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * RegsiterHandlersCompilerPass
 *
 */
class RegisterHandlersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('lexing_trade.trade_handler_manager')) {
            return;
        }

        $config = $container->getExtensionConfig('lexing_trade');
        $definition = $container->findDefinition('lexing_trade.trade_handler_manager');
        $configHandlers = $config[0]['handlers'];
        foreach ($configHandlers as $type=>$handlerServiceName) {
            $handlerDefinition = $container->findDefinition($handlerServiceName);
            $definition->addMethodCall('addHandler', [$type, $handlerDefinition]);
        }
    }
}