<?php

namespace ChronopostPickupPoint\Hook;

use ChronopostPickupPoint\Model\ChronopostPickupPointOrderQuery;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Model\ModuleQuery;

class BackOrderHook extends BaseHook
{
    public function onOrderEditDeliveryModuleBottom(HookRenderEvent $event): void
    {
        $orderId = $event->getArgument("order_id");
        $moduleId = $event->getArgument("module_id");
        $module = ModuleQuery::create()->findPk($moduleId);
        if ($module->getCode() !== "ChronopostPickupPoint") return;
        $chronopostOrder = ChronopostPickupPointOrderQuery::create()->filterByOrderId($orderId)->findOne();
        if (!$chronopostOrder) return;
        $event->add(
            $this->render(
                'ChronopostPickupPoint/order-edit.delivery-module-bottom.html',
                [
                    'delivery_type' => $chronopostOrder->getDeliveryType(),
                ]
            ));
    }

    public static function getSubscribedHooks(): array
    {
        return [
            "order-edit.delivery-module-bottom" => [
                [
                    "type" => "back",
                    "method" => "onOrderEditDeliveryModuleBottom"
                ],
            ],
        ];
    }
}
