<?php

namespace ChronopostPickupPoint\Hook;


use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class FrontHook extends BaseHook
{
    public function onOrderDeliveryExtra(HookRenderEvent $event)
    {
        $content = $this->render("ChronopostPickupPoint.html", $event->getArguments());
        $event->add($content);
    }
}