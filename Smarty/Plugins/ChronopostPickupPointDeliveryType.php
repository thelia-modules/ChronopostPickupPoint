<?php

namespace ChronopostPickupPoint\Smarty\Plugins;


use ChronopostPickupPoint\ChronopostPickupPoint;
use ChronopostPickupPoint\Config\ChronopostPickupPointConst;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\CountryArea;
use Thelia\Model\CountryQuery;
use Thelia\Model\Coupon;
use Thelia\Model\CouponQuery;
use Thelia\Module\Exception\DeliveryException;
use TheliaSmarty\Template\AbstractSmartyPlugin;
use TheliaSmarty\Template\SmartyPluginDescriptor;

class ChronopostPickupPointDeliveryType extends AbstractSmartyPlugin
{
    protected $requestStack;
    protected $dispatcher;

    /**
     * ChronopostPickupPointDeliveryType constructor.
     *
     * @param Request $request
     * @param EventDispatcherInterface|null $dispatcher
     */
    public function __construct(RequestStack $requestStack, EventDispatcherInterface $dispatcher = null)
    {
        $this->requestStack = $requestStack;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return array|SmartyPluginDescriptor[]
     */
    public function getPluginDescriptors()
    {
        return array(
            new SmartyPluginDescriptor("function", "chronopostPickupPointDeliveryType", $this, "chronopostPickupPointDeliveryType"),
            new SmartyPluginDescriptor("function", "chronopostPickupPointDeliveryPrice", $this, "chronopostPickupPointDeliveryPrice"),
            new SmartyPluginDescriptor("function", "chronopostPickupPointGetDeliveryTypesStatusKeys", $this, "chronopostPickupPointGetDeliveryTypesStatusKeys"),
        );
    }

    /**
     * @param $params
     * @param $smarty
     * @throws PropelException
     */
    public function chronopostPickupPointDeliveryPrice($params, $smarty)
    {
        $deliveryMode = $params["delivery-mode"];
        $country = CountryQuery::create()->findOneById($params["country"]);

        $request = $this->requestStack->getCurrentRequest();
        $cartWeight = $request->getSession()->getSessionCart($this->dispatcher)->getWeight();
        $cartAmount = $request->getSession()->getSessionCart($this->dispatcher)->getTaxedAmount($country);

        try {

            $countryAreas = $country->getCountryAreas();
            $areasArray = [];

            /** @var CountryArea $countryArea */
            foreach ($countryAreas as $countryArea) {
                $areasArray[] = $countryArea->getAreaId();
            }

            $price = (new ChronopostPickupPoint)->getMinPostage(
                $areasArray,
                $cartWeight,
                $cartAmount,
                $deliveryMode
            );

            $consumedCouponsCodes = $request->getSession()->getConsumedCoupons();

            foreach ($consumedCouponsCodes as $consumedCouponCode)  {
                $coupon = CouponQuery::create()
                    ->filterByCode($consumedCouponCode)
                    ->findOne();

                /** @var Coupon $coupon */
                if (null !== $coupon){
                    if ($coupon->getIsRemovingPostage()){
                        $price = 0;
                    }
                }
            }

        } catch (DeliveryException $ex) {
            $smarty->assign('isValidMode', false);
        }

        $smarty->assign('chronopostPickupPointDeliveryModePrice', $price);

    }

    /**
     * @param $params
     * @param $smarty
     */
    public function chronopostPickupPointDeliveryType($params, $smarty)
    {
        foreach (ChronopostPickupPointConst::getDeliveryTypesStatusKeys() as $deliveryTypeName => $statusKey) {
            $smarty->assign('is' . $deliveryTypeName . 'Enabled', (bool)ChronopostPickupPoint::getConfigValue($statusKey));
        }
    }

    /**
     * @param $params
     * @param $smarty
     */
    public function chronopostPickupPointGetDeliveryTypesStatusKeys($params, $smarty)
    {
        $smarty->assign('chronopostPickupPointDeliveryTypesStatusKeys', ChronopostPickupPointConst::getDeliveryTypesStatusKeys());
    }

}
