<?php

namespace Nnv\NotificationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Nnv\NotificationBundle\EventListener\NotificationListener;
use Symfony\Component\DependencyInjection\Reference;

/**
 * RegisterProvidersPass
 * 
 * 注册providers服务
 */
class RegisterProvidersPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('nnv_notificaiton.sms_provider_manager')) {
            return;
        }

        $config = $container->getExtensionConfig('nnv_notification');
        $configProviders = isset($config[0]['providers']) ? $config[0]['providers'] : null;

        // register sms providers
        $definition = $container->findDefinition('nnv_notificaiton.sms_provider_manager');
        $taggedServices = $container->findTaggedServiceIds('notification.sms');
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $providerName = $attributes['provider'];
                if ($configProviders && isset($configProviders['sms'], $configProviders['sms'][$providerName])) {
                    $providerDefinition = $container->findDefinition($id);
                    $providerDefinition->addMethodCall('setConfig', [$configProviders['sms'][$providerName]]);
                }

                $definition->addMethodCall('addProvider', array(
                    $providerName,
                    new Reference($id)
                ));
            }
        }
    }
}
