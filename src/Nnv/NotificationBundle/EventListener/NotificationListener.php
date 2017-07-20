<?php

namespace Nnv\NotificationBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Nnv\NotificationBundle\Event\NotifiableEvent;

/**
 * NotificationListener
 * 
 * @author kail
 */
class NotificationListener
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
    
    // deferNotifyEvent, CGI or message queue
    
    public function notifyEvent($event, $eventName, $dispatcher)
    {
        if (!is_a($event, NotifiableEvent::class)) {
            // exception, guide developers
            return;
        }
        
        $notifiables = $this->container->getParameter('nnv.notifiables');
        if (!isset($notifiables[$eventName])) {
            return;
        }
        
        $notifiable = $notifiables[$eventName];
        $user = $event->getUser();
        // @todo refactor
        if (isset($notifiable['weixin']) && (($user && $user->getOpenid()) || $event->hasArgument('openid'))) {
            $wxHelper = $this->container->get('nnv_wx.helper');
            $tplMsgCfg = $notifiable['weixin'];
            $twig = $this->container->get('twig');
            $serialized = [];
            foreach ($tplMsgCfg['data'] as $name=>$tpl) {
                $serialized[] = "$name#:#$tpl";
            }
            
            $serializedTpl = implode('###', $serialized);
            $serializedRendered = $twig->createTemplate($serializedTpl)->render($event->getArguments());
            $unserialziedArr = explode('###', $serializedRendered);
            $msgArr = [];
            foreach ($unserialziedArr as $str) {
                $arr = explode('#:#', $str);
                $msgArr[$arr[0]] = $arr[1];
            }
            $openid = $user && $user->getOpenid() ? $user->getOpenid() : $event->getArgument('openid');
            $url = $event->hasArgument('url') ? $event->getArgument('url') : (isset($tplMsgCfg['url']) ? $tplMsgCfg['url'] : null);
            $wxHelper->sendTplMsg($openid, $tplMsgCfg['tpl_id'], $msgArr, $url);
        }

        // @todo 细化notifiable event类型，sms, email, weixin?
        if (isset($notifiable['sms'])) {
            $smsProviderManager = $this->container->get('nnv_notificaiton.sms_provider_manager');
            $smsProviderManager->sendSms($notifiable['sms'], $event->getArguments(), $event->getArgument('mobile'));
        }
    }
}
