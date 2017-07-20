<?php

namespace Nnv\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Nnv\NotificationBundle\Event\NotifiableEvent;

/**
 * 
 * @Route("/notification")
 */
class NotificationController extends Controller
{
    /**
     * 测试消息
     * 
     * @Route("/test", name="nnv_notification_test")
     */
    public function testAction(Request $req)
    {   
        $dispatcher = $this->get('event_dispatcher');
        $notifiableEvent = new NotifiableEvent(null, [
            'openid' => 'o9u4awVMvV0GgVmjJyxa0SA1Bivo'
        ]);//o9u4awVMvV0GgVmjJyxa0SA1Bivo
        //o9u4awQIgaHpeYb68FeOOP4txlVk
        $dispatcher->dispatch('login.noti', $notifiableEvent);
        return new Response('event dispatched');
    }
}