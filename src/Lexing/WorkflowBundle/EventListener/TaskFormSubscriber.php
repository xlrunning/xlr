<?php

namespace Lexing\WorkflowBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class TaskFormSubscriber implements EventSubscriberInterface
{
    public function onSubmit(FormEvent $event)
    {
        // @todo validation在post_submit完成
        // dump($event->getForm()->getErrors(true));

        if (!$event->getForm()->isValid()) {
            // echo 'error';
        }

        dump($event);
        exit;

        // @todo create task entry
        // @todo 是否是想完成任务，是否能完成任务
        // @todo dispatch events (任务提交， 任务完成)
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SUBMIT => 'onSubmit'
        ];
    }
}