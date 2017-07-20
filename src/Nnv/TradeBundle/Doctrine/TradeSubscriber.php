<?php

namespace Nnv\TradeBundle\Doctrine;

use Doctrine\Common\EventSubscriber;
use Sofashion\OrderBundle\Entity\ProductOrder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Nnv\TradeBundle\Entity\Trade;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * 
 * TradeSubscriber
 * 
 * 订单状态相关
 */
class TradeSubscriber implements EventSubscriber
{
    /**
     *
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function getSubscribedEvents()
    {
        return array(
            Events::preUpdate
        );
    }
    
    public function preUpdate(PreUpdateEventArgs $args)
    {
//        $entity = $args->getEntity();
//        if (!($entity instanceof Trade) || !$args->hasChangedField('expressBill') || $args->getOldValue('expressBill')) {
//            return;
//        }
        
        // if sync, it'll conflict flush in its event listener
        // $dispatcher = $this->container->get('bbit_async_dispatcher.dispatcher'); // get dispatcher service
        // $dispatcher->addAsyncEvent('trade.delivered', new GenericEvent($entity));
        
        // $dispatcher = $this->container->get('event_dispatcher');
        // $dispatcher->dispatch('trade.delivered', new GenericEvent($entity));
    }
}