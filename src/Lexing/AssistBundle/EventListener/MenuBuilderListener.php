<?php

namespace Lexing\AssistBundle\EventListener;

use Sonata\AdminBundle\Event\ConfigureMenuEvent;

class MenuBuilderListener
{
    public function addMenuItems(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();
        return;

        $child = $menu->addChild('车城监控', [
            'uri' => 'http://baidu.com',
            #'route' => 'sonata_admin_dashboard',
        ])->setExtras([
            'on_top' => true,
            'icon' => '<i class="fa fa-eye"></i>',
            // roles
        ]);
        $menu->addChild('操作手册', [
            'uri' => 'http://baidu.com',
            #'route' => 'sonata_admin_dashboard',
        ])->setExtras([
            'on_top' => true,
            'icon' => '<i class="fa fa-book"></i>',
        ]);
//        <span class="pull-right-container">
//            <small class="label pull-right bg-red">3</small>
//            <small class="label pull-right bg-blue">17</small>
//        </span>
        $child2 = $menu->addChild('市场调研');
        $child2->addChild('金融时事', ['uri' => 'http://baidu.com']);
    }
}