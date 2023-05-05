<?php

namespace ChronopostPickupPoint\EventListeners;


use ChronopostPickupPoint\ChronopostPickupPoint;
use ChronopostPickupPoint\Config\ChronopostPickupPointConst;
use ChronopostPickupPoint\Model\ChronopostPickupPointOrder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;



class SetDeliveryType implements EventSubscriberInterface
{
    /** @var Request */
    protected $requestStack;

    /**
     * SetDeliveryType constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * @param $id
     * @return bool
     */
    protected function checkModule($id)
    {
        return $id == ChronopostPickupPoint::getModuleId();
    }

    /**
     * @param OrderEvent $orderEvent
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function saveChronopostPickupPointOrder(OrderEvent $orderEvent)
    {
        if ($this->checkModule($orderEvent->getOrder()->getDeliveryModuleId())) {

            $request = $this->getRequest();
            $chronopostOrder = new ChronopostPickupPointOrder();

            $orderId = $orderEvent->getOrder()->getId();

            foreach (ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_DELIVERY_CODES as $name => $code) {
                if ($code === $request->getSession()->get('ChronopostPickupPointDeliveryType')) {
                    $chronopostOrder
                        ->setDeliveryType($name)
                        ->setDeliveryCode($code)
                    ;
                }

                if ($request->getSession()->get('pickup_address') !== null) {
                    $idRelais = json_decode($request->getSession()->get('pickup_address'))->id;
                    $chronopostOrder->setIdRelais($idRelais);
                }

            }

            $chronopostOrder
                ->setOrderId($orderId)
                ->save();
        }
    }

    /**
     * @param OrderEvent $orderEvent
     * @return null
     */
    public function setChronopostPickupPointDeliveryType(OrderEvent $orderEvent)
    {
        if ($this->checkModule($orderEvent->getDeliveryModule())) {
            $request = $this->getRequest();

            $request->getSession()->set('ChronopostAddressId', $orderEvent->getDeliveryAddress());
            $request->getSession()->set('ChronopostPickupPointDeliveryType', $request->get('deliveryModuleOptionCode'));
        }

        return ;
    }


    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            TheliaEvents::ORDER_SET_DELIVERY_MODULE => array('setChronopostPickupPointDeliveryType', 64),
            TheliaEvents::ORDER_BEFORE_PAYMENT => array('saveChronopostPickupPointOrder', 256),
        );
    }
}