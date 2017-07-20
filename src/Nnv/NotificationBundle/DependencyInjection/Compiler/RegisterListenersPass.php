<?php

namespace Nnv\NotificationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Nnv\NotificationBundle\EventListener\NotificationListener;
use Symfony\Component\DependencyInjection\Reference;

/**
 * RegisterListenersPass
 * 
 * 根据配置将监听需要通知的事件
 */
class RegisterListenersPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
//        $cfgVal = $container
//            ->getParameterBag()
//            ->resolveValue($container->getParameter('param_name'));
        $config = $container->getExtensionConfig('nnv_notification');
        // $config[0]['notifiables']
        $definition = $container
            ->register('nnv.notification_listener', NotificationListener::class)
            ->addArgument(new Reference('service_container'));
        $notifiables = $config[0]['notifiables'];
        foreach ($notifiables as $event=>$notifiable) {
            $definition->addTag('kernel.event_listener', array('event' => $event, 'method' => 'notifyEvent'));
        }
    }
}
