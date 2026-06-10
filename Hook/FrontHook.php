<?php

declare(strict_types=1);

namespace ChronopostPickupPoint\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class FrontHook extends BaseHook
{
    public static function getSubscribedHooks(): array
    {
        return [
            'order-delivery.extra' => [
                ['type' => 'front', 'method' => 'onOrderDeliveryExtra'],
            ],
        ];
    }

    public function onOrderDeliveryExtra(HookRenderEvent $event): void
    {
        $content = $this->render('ChronopostPickupPoint.html', $event->getArguments());
        $event->add($content);
    }
}
