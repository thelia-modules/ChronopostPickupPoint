<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace ChronopostPickupPoint;

use ChronopostPickupPoint\Config\ChronopostPickupPointConst;
use ChronopostPickupPoint\Model\ChronopostPickupPointAreaFreeshippingQuery;
use ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryMode;
use ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryModeQuery;
use ChronopostPickupPoint\Model\ChronopostPickupPointPriceQuery;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Symfony\Component\Finder\Finder;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Install\Database;
use Thelia\Model\Country;
use Thelia\Model\CountryArea;
use Thelia\Model\Lang;
use Thelia\Model\LangQuery;
use Thelia\Model\Message;
use Thelia\Model\MessageQuery;
use Thelia\Model\OrderPostage;
use Thelia\Model\State;
use Thelia\Module\AbstractDeliveryModuleWithState;
use Thelia\Module\Exception\DeliveryException;

class ChronopostPickupPoint extends AbstractDeliveryModuleWithState
{
    /** @var string */
    const DOMAIN_NAME = 'chronopostpickuppoint';

    const CHRONOPOST_CONFIRMATION_MESSAGE_NAME = 'chronopost_pickup_point_confirmation_message_name';

    const CHRONOPOST_TAX_RULE_ID = 'chronopost_pickup_point_tax_rule_id';

    /**
     * @param ConnectionInterface|null $con
     * @throws PropelException
     */
    public function postActivation(ConnectionInterface $con = null): void
    {
        try {
            /** Security to not erase user configuration on reactivation */
            ChronopostPickupPointDeliveryModeQuery::create()->findOne();
            ChronopostPickupPointAreaFreeshippingQuery::create()->findOne();
        } catch (\Exception $e) {
            $database = new Database($con->getWrappedConnection());
            $database->insertSql(null, array(__DIR__ . '/Config/TheliaMain.sql'));
        }

        $langs = LangQuery::create()->filterByActive(1)->find();

        /** Default config values */
        $defaultConfig = [
            ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_CODE_CLIENT => null,
            ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_PASSWORD => null,
        ];

        /** Defaults the delivery types status as false */
        foreach (ChronopostPickupPointConst::getDeliveryTypesStatusKeys() as $statusKey) {
            $defaultConfig[$statusKey] = false;
        }

        /** Set the default config values in the DB table if it doesn't exists yet */
        foreach ($defaultConfig as $key => $value) {
            if (null === self::getConfigValue($key, null)) {
                self::setConfigValue($key, $value);
            }
        }

        /** Set the delivery types as not enabled in the ChronopostPickupPointDeliveryMode table
         * when activating the module for the first time
         */
        foreach (ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_DELIVERY_CODES as $title => $code) {
            if (null === $this->isDeliveryTypeSet($code)) {
                $this->setDeliveryType($code, $title, $langs);
            }
        }

        if (null === MessageQuery::create()->findOneByName(self::CHRONOPOST_CONFIRMATION_MESSAGE_NAME)) {
            $message = new Message();

            $message
                ->setName(self::CHRONOPOST_CONFIRMATION_MESSAGE_NAME)
                ->setHtmlLayoutFileName('order_shipped.html')
                ->setTextLayoutFileName('order_shipped.txt')
                ->setLocale('en_US')
                ->setTitle('Order send confirmation')
                ->setSubject('Order send confirmation')

                ->setLocale('fr_FR')
                ->setTitle('Confirmation d\'envoi de commande')
                ->setSubject('Confirmation d\'envoi de commande')

                ->save()
            ;
        }

        if (!self::getConfigValue(self::CHRONOPOST_TAX_RULE_ID)) {
            self::setConfigValue(self::CHRONOPOST_TAX_RULE_ID, null);
        }
    }

    public function update($currentVersion, $newVersion, ConnectionInterface $con = null):void
    {
        $sqlToExecute = [];
        $finder = new Finder();
        $sort = function (\SplFileInfo $a, \SplFileInfo $b) {
            $a = strtolower(substr($a->getRelativePathname(), 0, -4));
            $b = strtolower(substr($b->getRelativePathname(), 0, -4));
            return version_compare($a, $b);
        };

        $files = $finder->name('*.sql')
            ->in(__DIR__ . "/Config/Update/")
            ->sort($sort);

        foreach ($files as $file) {
            if (version_compare($file->getFilename(), $currentVersion, ">")) {
                $sqlToExecute[$file->getFilename()] = $file->getRealPath();
            }
        }

        $database = new Database($con);

        foreach ($sqlToExecute as $version => $sql) {
            $database->insertSql(null, [$sql]);
        }

        $langs = LangQuery::create()->filterByActive(1)->find();
        // Add missing delivery types when updating
        foreach (ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_DELIVERY_CODES as $title => $code) {
            if (null === $this->isDeliveryTypeSet($code)) {
                $this->setDeliveryType($code, $title, $langs);
            }
        }

    }

    /**
     * Check if a given delivery type exists in the ChronopostPickupPointDeliveryMode table
     *
     * @param $code
     * @return ChronopostPickupPointDeliveryMode
     */
    public function isDeliveryTypeSet($code)
    {
        return ChronopostPickupPointDeliveryModeQuery::create()->findOneByCode($code);
    }

    /**
     * Add a delivery type to the ChronopostPickupPointDeliveryMode table
     *
     * @param $code
     * @param $title
     */
    public function setDeliveryType($code, $title, $langs)
    {
        $newDeliveryType = new ChronopostPickupPointDeliveryMode();

        try {
            $newDeliveryType
                ->setCode($code)
                ->setFreeshippingActive(false)
                ->setFreeshippingFrom(null)
                ->save();

            /** @var Lang $lang */
            foreach ($langs as $lang) {
                $newDeliveryType
                    ->setLocale($lang->getLocale())
                    ->setTitle($title)
                    ->save();
            }
        } catch (\Exception $e) {

        }
    }

    /**
     * Verify if the area asked by the user is in the list of areas added to the shipping zones
     * and has correctly defined price slices
     *
     * @param Country $country
     * @param State $state the state to deliver to.
     * @return bool
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function isValidDelivery(Country $country, State $state = null)
    {
        if (empty($this->getAllAreasForCountry($country))) {
            return false;
        }

        $countryAreas = $country->getCountryAreas();
        $areasArray = [];

        /** @var CountryArea $countryArea */
        foreach ($countryAreas as $countryArea) {
            $areasArray[] = $countryArea->getAreaId();
        }

        $prices = ChronopostPickupPointPriceQuery::create()
            ->filterByAreaId($areasArray)
            ->findOne()
        ;

        $freeShipping = ChronopostPickupPointDeliveryModeQuery::create()
            ->filterByFreeshippingActive(true)
            ->findOne()
        ;

        /** Check if Chronopost delivers in the asked area */
        if (null !== $prices || null !== $freeShipping) {

            try {
                $this->getPostage($country, $state);
            }catch (\Exception $exception){
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Return the delivery type of an ongoing order.
     *
     * @param Request|Session $request
     * @return null|string
     */
    public function getDeliveryType($request)
    {
        $deliveryMode = $request->get('deliveryModuleOptionCode');

        $deliveryCodes = array_change_key_case(ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_DELIVERY_CODES, CASE_LOWER);

        if (array_key_exists(strtolower($deliveryMode),$deliveryCodes)) {
            return $deliveryCodes[strtolower($deliveryMode)];
        }

        if(in_array($deliveryMode, ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_DELIVERY_CODES, true)){
            return $deliveryMode;
        }

        return null;
    }

    /**
     * Return the postage price for a given area, cart weight, cart amount, and delivery type
     *
     * @param $areaId
     * @param $weight
     * @param int $cartAmount
     * @param null $deliveryCode
     * @return int
     * @throws \Exception
     */
    public static function getPostageAmount($areaId, $weight, $cartAmount = 0, $deliveryCode = null)
    {
        if (null === $deliveryType = ChronopostPickupPointDeliveryModeQuery::create()->findOneByCode($deliveryCode)) {
            throw new \Exception("The delivery code given is not supported by the module.");
        }

        /** Check if freeshipping is activated for this delivery type */
        try {
            $freeShipping = $deliveryType->getFreeshippingActive();
        } catch (\Exception $e) {
            $freeShipping = false;
        }

        /** Get the total cart price needed to have a free shipping for all areas, if it exists */
        try {
            $freeShippingFrom = $deliveryType->getFreeshippingFrom();
        } catch (\Exception $er) {
            $freeShippingFrom = null;
        }

        /** Set the initial postage price as 0 */
        $postage = 0;

        /** If free shipping is enabled, skip and return 0 */
        if (!$freeShipping) {

            /** If a minimum price for free shipping is defined and the amount of the cart reach this limit, return 0. */
            if (null !== $freeShippingFrom && $freeShippingFrom <= $cartAmount) {
                return $postage;
            }

            /** Get the minimum price for free shipping in the area of the order */
            $cartAmountFreeShipping = ChronopostPickupPointAreaFreeshippingQuery::create()
                ->filterByAreaId($areaId)
                ->filterByDeliveryModeId($deliveryType->getId())
                ->findOne();

            if (null !== $cartAmountFreeShipping) {
                $cartAmountFreeShipping = $cartAmountFreeShipping->getCartAmount();
            }

            /** If the cart price is superior to the minimum price for free shipping in the area of the order,
             * return the postage as free.
             */
            if ($cartAmountFreeShipping !== null && $cartAmountFreeShipping <= $cartAmount) {
                return 0;
            }

            /** Search the list of prices and order it in ascending order */
            $areaPrices = ChronopostPickupPointPriceQuery::create()
                ->filterByDeliveryModeId($deliveryType->getId())
                ->filterByAreaId($areaId)
                ->filterByWeightMax($weight, Criteria::GREATER_EQUAL)
                ->_or()
                ->filterByWeightMax(null)
                ->filterByPriceMax($cartAmount, Criteria::GREATER_EQUAL)
                ->_or()
                ->filterByPriceMax(null)
                ->orderByWeightMax()
                ->orderByPriceMax();

            /** Find the correct postage price for the cart weight and price according to the area and delivery mode in $areaPrices*/
            $firstPrice = $areaPrices
                ->find()
                ->getFirst()
            ;

            /** If no price was found, return null */
            if (null === $firstPrice) {
                return null;
            }

            $postage = $firstPrice->getPrice();
        }

        return $postage;
    }

    /**
     * Return the minimum postage price of a list of areas, for a given cart weight, price, and delivery type.
     *
     * @param $country
     * @param $cartWeight
     * @param $cartAmount
     * @param $deliveryType
     * @return OrderPostage
     */
    public function getMinPostage($country, $cartWeight, $cartAmount, $deliveryType, $locale)
    {
        $minPostage = null;

        /** Check what areas are covered in the shipping zones defined by the admin */
        $areaIdArray = $this->getAllAreasForCountry($country);
        if (empty($areaIdArray)) {
            throw new DeliveryException("Your delivery country is not covered by Chronopost");
        }

        foreach ($areaIdArray as $areaId) {
            try {
                $postage = self::getPostageAmount($areaId, $cartWeight, $cartAmount, $deliveryType);
                if (null === $postage) {
                    continue ;
                }
                if ($minPostage === null || $postage < $minPostage) {
                    $minPostage = $postage;
                    if ($minPostage === 0) {
                        break;
                    }
                }
            } catch (\Exception $ex) {
                throw new DeliveryException($ex->getMessage()); //todo : Make a better catch, duh
            }
        }

        return $this->buildOrderPostage($minPostage, $country, $locale, self::getConfigValue(self::CHRONOPOST_TAX_RULE_ID));
    }

    /**
     * Return an array of the codes of all delivery types that have been activated
     * in the backOffice configuration page.
     *
     * @return array
     */
    public static function getActivatedDeliveryTypes()
    {
        $config = ChronopostPickupPointConst::getConfig();
        $deliveryTypes = ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_DELIVERY_CODES;
        $activatedDeliveryTypes = [];

        foreach (ChronopostPickupPointConst::getDeliveryTypesStatusKeys() as $deliveryTypeName => $statusKey) {
            if (true === (bool)$config[$statusKey]) {
                $activatedDeliveryTypes[] = $deliveryTypes[$deliveryTypeName];
            }
        }

        return $activatedDeliveryTypes;
    }

    /**
     * Return the postage of an ongoing order, or the minimum expected postage before the user chooses what delivery types he wants.
     *
     * @param Country $country
     * @param null $deliveryType
     * @param State $state the state to deliver to.
     * @return float|int|\Thelia\Model\OrderPostage
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getPostage(Country $country, State $state = null)
    {
        $request = $this->getRequest();

        $cartWeight = $request->getSession()->getSessionCart($this->getDispatcher())->getWeight();
        $cartAmount = $request->getSession()->getSessionCart($this->getDispatcher())->getTaxedAmount($country);


        /** Get the delivery type of an ongoing order by looking at the request */
        $deliveryType = $this->getDeliveryType($request);

        /** If no delivery type was found, search again in the session. */
        if (null === $deliveryType) {
            $deliveryType = $this->getDeliveryType($request->getSession());
        }

        $deliveryArray[] = $deliveryType;

        /** If still no delivery type is found, create an array of all activated delivery types to search
         *  which one has the lowest delivery price.
         */
        if (null === $deliveryType) {
            $deliveryArray = $this->getActivatedDeliveryTypes();
        }

        $postage = null;

        /** If no delivery type was given, the loop should continue until the postage for each delivery types was
         *  found, then return the minimum one. Otherwise, the loop should stop after the first iteration.
         */
        foreach ($deliveryArray as $deliveryMode) {
            try {
                $deliveryModePostage = $this->getMinPostage($country, $cartWeight, $cartAmount, $deliveryMode, $request->getSession()?->getLang()->getLocale());
            } catch (\Exception $exception){
                continue;
            }

            if (null === $postage || $postage > $deliveryModePostage) {
                $postage = $deliveryModePostage;
            }
        }

        /** If no postage was found, we throw an exception */
        if (null === $postage) {
            throw new DeliveryException("Chronopost delivery unavailable for your cart weight or delivery country");
        }

        /** Get the postage for the shipping zones we've just got */

        ///** If delivery is free, set it to a minimal number so the price will still appear. It will be rounded up to 0 */
        //if (0 == $postage) {
        //    $postage = 0.000001;
        //}

        return $postage;
    }

    /**
     * Returns ids of area containing this country and covered by this module
     * @param Country $country
     * @return array Area ids
     */
    public function getAllAreasForCountry(Country $country)
    {
        $areaArray = [];

        $sql = "SELECT ca.area_id as area_id FROM country_area ca
               INNER JOIN area_delivery_module adm ON (ca.area_id = adm.area_id AND adm.delivery_module_id = :p0)
               WHERE ca.country_id = :p1";

        $con = Propel::getConnection();

        $stmt = $con->prepare($sql);
        $stmt->bindValue(':p0', $this->getModuleModel()->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':p1', $country->getId(), PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $areaArray[] = $row['area_id'];
        }

        return $areaArray;
    }

    public static function configureServices(ServicesConfigurator $servicesConfigurator): void
    {
        $servicesConfigurator->load(self::getModuleCode().'\\', __DIR__)
            ->exclude([THELIA_MODULE_DIR . ucfirst(self::getModuleCode()). "/I18n/*"])
            ->autowire(true)
            ->autoconfigure(true);
    }

    public function getDeliveryMode()
    {
        return "pickup";
    }
}
