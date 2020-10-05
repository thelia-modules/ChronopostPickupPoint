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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\ModuleQuery;
use Thelia\Model\OrderAddress;
use Thelia\Model\OrderAddressQuery;

class SetDeliveryAddress implements EventSubscriberInterface
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param OrderEvent $event
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function updateDeliveryAddress(OrderEvent $event)
    {
        if ($event->getOrder()->getDeliveryModuleId() === ModuleQuery::create()->filterByCode('ChronopostPickupPoint')->findOne()->getId()){
            $request = $this->getRequest();

            $tmp_address = ChronopostPickupPointOrderAddressQuery::create()
                ->filterById($request->getSession()->get('ChronopostPickupPointId'))->findOne();

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


    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::ORDER_BEFORE_PAYMENT => ['updateDeliveryAddress', 128]
        ];
    }
}