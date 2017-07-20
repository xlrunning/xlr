<?php

namespace Nnv\WxBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * 
 * WxListener
 * 
 * 发送模板消息
 */
class WxListener
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
    
    public function onTplMsgsSend(GenericEvent $event)
    {
        $wxhelper = $this->container->get('nnv_wx.helper');
        $tplMsgs = $event->getArgument('tplMsgs');
        foreach ($tplMsgs as $tplMsg) {
            $wxhelper->sendTplMsg($tplMsg[0], $tplMsg[1], $tplMsg[2], $tplMsg[3]);
        }
    }
    
    public function onKefuMsgSend(GenericEvent $event)
    {
        $msgObj = $event->getSubject();
        $wxhelper = $this->container->get('nnv_wx.helper');
        $wxhelper->kefuSendMsg($msgObj->openid, $msgObj->articles);
    }
}