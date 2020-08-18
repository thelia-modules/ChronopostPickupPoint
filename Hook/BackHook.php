<?php

namespace ChronopostPickupPoint\Hook;


use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class BackHook extends BaseHook
{
    public function onModuleConfiguration(HookRenderEvent $event)
    {
        $event->add($this->render('ChronopostPickupPoint/ChronopostPickupPointConfig.html'));
    }

    public function onModuleConfigJs(HookRenderEvent $event)
    {
        $event->add($this->render('ChronopostPickupPoint/module-config-js.html'));
    }
}