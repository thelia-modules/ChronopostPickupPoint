<?php

namespace ChronopostPickupPoint\EventListeners;

use ChronopostPickupPoint\ChronopostPickupPoint;
use ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryModeQuery;
use ChronopostPickupPoint\Model\ChronopostPickupPointPriceQuery;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Model\AreaDeliveryModuleQuery;
use Thelia\Model\ModuleConfigQuery;

class ConfigListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'module.config' => [
                'onModuleConfig', 128
            ],
        ];
    }

    public function onModuleConfig(GenericEvent $event): void
    {
        $subject = $event->getSubject();

        if ($subject !== "HealthStatus") {
            throw new \RuntimeException('Event subject does not match expected value');
        }

        $shippingZoneConfig = AreaDeliveryModuleQuery::create()
            ->filterByDeliveryModuleId(ChronopostPickupPoint::getModuleId())
            ->find();

        $freeShipping= ChronopostPickupPointDeliveryModeQuery::create()
            ->filterByCode(['86','5X','5Y','5E'])
            ->find();

        $configModule = ModuleConfigQuery::create()
            ->filterByName(['chronopost_pickup_point_code', 'chronopost_pickup_point_password'])
            ->find();

        $slicesConfig = ChronopostPickupPointPriceQuery::create()
            ->find();

        $moduleConfig = [];
        $moduleConfig['module'] = ChronopostPickupPoint::getModuleCode();
        $configsCompleted = true;

        if ($configModule->count() === 0) {
            $configsCompleted = false;
        }

        if ($shippingZoneConfig->count() === 0) {
            $configsCompleted = false;
        }

        foreach ($configModule as $config) {
            if ($config->getValue() === null || $config->getValue() === "") {
                $configsCompleted = false;
            }
        }

        $hasFreeShippingFrom = false;
        foreach ($freeShipping as $shipping) {
            if ($shipping->getFreeshippingFrom() !== null) {
                $hasFreeShippingFrom = true;
                break;
            }
        }

        $hasSlices = false;
        if ($slicesConfig->count() > 0) {
            $hasSlices = true;
        }

        $hasFreeShipping = false;
        foreach ($freeShipping as $shipping) {
            if ($shipping->getFreeshippingActive() === true) {
                $hasFreeShipping = true;
                break;
            }
        }

        if (!$hasFreeShipping && !$hasSlices && !$hasFreeShippingFrom) {
            $configsCompleted = false;
        }

        $moduleConfig['completed'] = $configsCompleted;

        $event->setArgument('chronopost_pickup_point.config', $moduleConfig);



    }
}
