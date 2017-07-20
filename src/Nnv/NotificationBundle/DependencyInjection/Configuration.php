<?php

namespace Nnv\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nnv_notification');

        $this->addProvidersSection($rootNode);
        $this->addNotifiablesSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addProvidersSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('providers')
                ->children()
                    ->arrayNode('sms')
                    ->prototype('array')
                    ->prototype('scalar')

                    ->end()
                    ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    public function addNotifiablesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('notifiables')
                ->prototype('array')
                ->children()
                    ->arrayNode('weixin')
                    ->children()
                        ->scalarNode('tpl_id')->isRequired()->end()
                        ->scalarNode('url')->end()
                        ->arrayNode('data')->prototype('scalar')->end()->end()
                    ->end()
                    ->end()
                    ->arrayNode('sms')
                        ->prototype('array')->end()
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end()
        ->end();
    }
}
