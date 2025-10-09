<?php

namespace ChronopostPickupPoint\Smarty\Plugins;

use ChronopostPickupPoint\ChronopostPickupPoint;
use ChronopostPickupPoint\Config\ChronopostPickupPointConst;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Model\CountryQuery;
use Thelia\Model\Coupon;
use Thelia\Model\CouponQuery;
use Thelia\Module\Exception\DeliveryException;
use TheliaSmarty\Template\AbstractSmartyPlugin;
use TheliaSmarty\Template\SmartyPluginDescriptor;

class ChronopostPickupPointDeliveryType extends AbstractSmartyPlugin
{
    public function __construct(protected RequestStack $requestStack, protected EventDispatcherInterface $dispatcher)
    {
    }

    /**
     * @return SmartyPluginDescriptor[]
     */
    public function getPluginDescriptors(): array
    {
        return array(
            new SmartyPluginDescriptor("function", "chronopostPickupPointDeliveryType", $this, "chronopostPickupPointDeliveryType"),
            new SmartyPluginDescriptor("function", "chronopostPickupPointDeliveryPrice", $this, "chronopostPickupPointDeliveryPrice"),
            new SmartyPluginDescriptor("function", "chronopostPickupPointGetDeliveryTypesStatusKeys", $this, "chronopostPickupPointGetDeliveryTypesStatusKeys"),
        );
    }

    /**
     * @throws PropelException
     */
    public function chronopostPickupPointDeliveryPrice($params, $smarty): void
    {
        $deliveryMode = $params["delivery-mode"];
        $country = CountryQuery::create()->findOneById($params["country"]);

        $request = $this->requestStack->getCurrentRequest();
        $cartWeight = $request->getSession()->getSessionCart($this->dispatcher)->getWeight();
        $cartAmount = $request->getSession()->getSessionCart($this->dispatcher)->getTaxedAmount($country);

        try {

            $price = (new ChronopostPickupPoint)->getMinPostage(
                $country,
                $cartWeight,
                $cartAmount,
                $deliveryMode,
                $request->getSession()->getLang()->getLocale()
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

    public function chronopostPickupPointDeliveryType($params, $smarty): void
    {
        foreach (ChronopostPickupPointConst::getDeliveryTypesStatusKeys() as $deliveryTypeName => $statusKey) {
            $smarty->assign('is' . $deliveryTypeName . 'Enabled', (bool)ChronopostPickupPoint::getConfigValue($statusKey));
        }
    }

    public function chronopostPickupPointGetDeliveryTypesStatusKeys($params, $smarty): void
    {
        $smarty->assign('chronopostPickupPointDeliveryTypesStatusKeys', ChronopostPickupPointConst::getDeliveryTypesStatusKeys());
    }
}
