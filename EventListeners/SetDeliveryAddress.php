<?php
/**
 * Created by PhpStorm.
 * User: nicolasbarbey
 * Date: 18/09/2020
 * Time: 13:46
 */

namespace ChronopostPickupPoint\EventListeners;


use ChronopostPickupPoint\Model\ChronopostPickupPointOrderAddress;
use ChronopostPickupPoint\Model\ChronopostPickupPointOrderAddressQuery;
use ChronopostPickupPoint\Service\ChronopostPickupPointService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\ModuleQuery;
use Thelia\Model\OrderAddress;
use Thelia\Model\OrderAddressQuery;

class SetDeliveryAddress implements EventSubscriberInterface
{
    protected $requestStack;

    public function __construct(
        RequestStack $requestStack,
        private ChronopostPickupPointService $chronopostPickupPointService
    ) {
        $this->requestStack = $requestStack;
    }

    public function getRequest()
    {
        return $this->requestStack;
    }

    /**
     * @param OrderEvent $event
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function updateDeliveryAddress(OrderEvent $event)
    {
        if ($event->getOrder()->getDeliveryModuleId() === ModuleQuery::create()->filterByCode('ChronopostPickupPoint')->findOne()->getId()){
            $request = $this->requestStack->getCurrentRequest();
            if (null === $request || !$request->hasSession()) return;
            if (!$request->getSession()->has('pickup')) return;
            $address = $request->getSession()->get('pickup')['address'];
            $tmp_address = $this->chronopostPickupPointService->saveAddress(
                company: $address['company'],
                address1: $address['address1'],
                address2: $address['address2'],
                address3: $address['address3'],
                countryIsoAlpha2: $address['countryCode'],
                zipCode: $address['zipCode'],
                city: $address['city'],
            );
            $request->getSession()->remove('pickup');
            if ($tmp_address){
                $orderAddr = OrderAddressQuery::create()
                    ->filterById($event->getOrder()->getDeliveryOrderAddressId())
                    ->findOne();

                if ($orderAddr){
                    $orderAddr
                        ->setCompany($tmp_address->getCompany())
                        ->setAddress1($tmp_address->getAddress1())
                        ->setAddress2($tmp_address->getAddress2())
                        ->setAddress3($tmp_address->getAddress3())
                        ->setZipcode($tmp_address->getZipcode())
                        ->setCity($tmp_address->getCity())
                        ->save()
                    ;
                }
            }
        }
    }


    public static function getSubscribedEvents(): array
    {
        return [
            TheliaEvents::ORDER_BEFORE_PAYMENT => ['updateDeliveryAddress', 128]
        ];
    }
}
