<?php

namespace Nnv\NotificationBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Nnv\UserBundle\Entity\User;

/**
 * NotifiableEvent
 * GenericEvent足够用，但为区分通知的用途专门定义
 *
 * @author kail
 */
class NotifiableEvent extends GenericEvent
{
    // notified result, save
    // notified at sms, wx, mail
    
    // getUserNotifyTo
    
    public function setUser(User $user)
    {
        // $this->user = $user;
        $this->setArgument('user', $user);
        
        return $this;
    }
    
    public function getUser()
    {
        return $this->hasArgument('user') ? $this->getArgument('user') : null;
    }
    
    // setUrl, getUrl, link
}