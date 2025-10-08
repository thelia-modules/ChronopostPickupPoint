<?php

namespace ChronopostPickupPoint\EventListeners;

use ChronopostPickupPoint\ChronopostPickupPoint;
use ChronopostPickupPoint\Config\ChronopostPickupPointConst;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\Delivery\DeliveryPostageEvent;
use Thelia\Core\Event\TheliaEvents;

class Postage implements EventSubscriberInterface
{
    public function __construct(private RequestStack $requestStack)
    {}

    public function getRequest(): Request
    {
        return $this->requestStack->getCurrentRequest();
    }
    public function moduleDeliveryPostage(DeliveryPostageEvent $event)
    {
        if (!$this->checkModule($event->getModule())) {
            return;
        }
        $request = $this->getRequest();
        $deliveryType = $request->getSession()->get('ChronopostPickupPointDeliveryType');
        if (!in_array($deliveryType, ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_DELIVERY_CODES)) {
            return;
        }
        $postage = (new ChronopostPickupPoint())->getMinPostage($event->getCountry(), $event->getCart()->getWeight(), $event->getCart()->getTaxedAmount($event->getCountry()), $deliveryType, $request->getLocale());
        $event->setPostage($postage);

    }

    protected function checkModule($module)
    {
        return $module instanceof ChronopostPickupPoint;
    }

    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::MODULE_DELIVERY_GET_POSTAGE => ['moduleDeliveryPostage', 128],
        ];
    }
}
