<?php

namespace Nnv\FormBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * ChoicePopulatorPass
 *
 */
class ChoicePopulatorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has('nnv.form.choice_populator')) {
            return;
        }

        $definition = $container->findDefinition('nnv.form.choice_populator');
        $taggedServices = $container->findTaggedServiceIds('nnv.form.choice_populator');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addPopulator', array(
                    $attributes['from'],
                    $attributes['to'],
                    new Reference($id)
                ));
            }
        }
    }
}