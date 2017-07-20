<?php

namespace Lexing\TradeBundle\Handler;

use Lexing\TradeBundle\Content\TradeContentInterface;
use Lexing\TradeBundle\Content\Transformer\TradeContentTransformerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexing\TradeBundle\Event\TradeEvent;
use Lexing\TradeBundle\Event\TradeEvents;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class TradeHandlerManager
 *
 * 调用trade被实际应用到的业务的处理逻辑
 *
 * @package Lexing\TradeBundle\Handler
 */
class TradeHandlerManager implements EventSubscriberInterface
{
    use ContainerAwareTrait;

    /**
     * type => handler
     * @var array
     */
    private $handlers = [];

    /**
     * class name => type
     * @var array
     */
    private $contentTradeTypes = [];

    /**
     * @param string $type
     * @param TradeHandler $handler
     * @return $this
     */
    public function addHandler($type, TradeHandler $handler)
    {
        $this->handlers[$type] = $handler;
        $this->contentTradeTypes[$handler->getContentClass()] = $type;
        return $this;
    }

    /**
     * @param string $className
     * @return string
     */
    public function getContentTradeType($className)
    {
        return isset($this->contentTradeTypes[$className]) ? $this->contentTradeTypes[$className] : null;
    }

    /**
     * @param string $type
     * @return TradeHandler
     */
    public function getHandler($type)
    {
        return $this->handlers[$type];
    }

    /**
     * @param TradeEvent $event
     * @return mixed
     */
    public function onCreate(TradeEvent $event)
    {
        $trade = $event->getTrade();
        assert(isset($this->handlers[$trade->getType()]));
        $handler = $this->handlers[$trade->getType()];
        return $handler->onCreate($trade);
    }

    /**
     * @param TradeEvent $event
     * @return mixed
     */
    public function onPaid(TradeEvent $event)
    {
        $trade = $event->getTrade();
        assert(isset($this->handlers[$trade->getType()]));
        $handler = $this->handlers[$trade->getType()];
        return $handler->onPaid($trade);
    }

    public static function getSubscribedEvents()
    {
        return [
            TradeEvents::CREATE => 'onCreate',
            TradeEvents::PAID => 'onPaid',
        ];
    }
}