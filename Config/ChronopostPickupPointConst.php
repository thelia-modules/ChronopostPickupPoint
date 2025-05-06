<?php

namespace ChronopostPickupPoint\Config;


use ChronopostPickupPoint\ChronopostPickupPoint;
use Symfony\Component\Filesystem\Filesystem;
use Thelia\Model\ConfigQuery;

class ChronopostPickupPointConst
{
    /** Delivery types Name => Code */
    const CHRONOPOST_PICKUP_POINT_DELIVERY_CODES = [
        "ChronoRelais"          => "86",
        "Chrono2ShopDirect"     => "5X",
        "Chrono2ShopEurope"     => "6B",
        "Chrono2ShopRetour"     => "5Y",
        "Shop2ShopFrance"       => "5E",
        //"Shop2ShopEurope"       => "5h",
    ];
    /** @TODO Add other delivery types */

    /** Chronopost shipper identifiers */
    const CHRONOPOST_PICKUP_POINT_CODE_CLIENT                    = "chronopost_pickup_point_code";
    const CHRONOPOST_PICKUP_POINT_PASSWORD                       = "chronopost_pickup_point_password";


    /** WSDL for the Chronopost Shipping Service */
    const CHRONOPOST_PICKUP_POINT_SHIPPING_SERVICE_WSDL              = "https://ws.chronopost.fr/shipping-cxf/ShippingServiceWS?wsdl";
    const CHRONOPOST_PICKUP_POINT_RELAY_SEARCH_SERVICE_WSDL          = "https://ws.chronopost.fr/recherchebt-ws-cxf/PointRelaisServiceWS?wsdl";
    const CHRONOPOST_PICKUP_POINT_COORDINATES_SERVICE_WSDL           = "https://ws.chronopost.fr/rdv-cxf/services/CreneauServiceWS?wsdl";
    /** @TODO Add other WSDL config key */

    /** @Unused */
    const CHRONOPOST_PICKUP_POINT_TRACKING_URL                   = "https://ws.chronopost.fr/tracking-cxf/TrackingServiceWS/trackSkybillV2";


    /** @Unused */
    public function getTrackingURL()
    {
        $URL = self::CHRONOPOST_PICKUP_POINT_TRACKING_URL;
        $URL .= "language=" . "fr_FR"; //todo Make locale a variable
        $URL .= "&skybillNumber=" . "XXX"; //todo Use real skybill Number -> getTrackingURL(variable)

        return $URL;
    }

    /** Local static config value, used to limit the number of calls to the DB  */
    protected static $config = null;

    /**
     * Set the local static config value
     */
    public static function setConfig()
    {
        $config = [
            /** Chronopost basic informations */
            self::CHRONOPOST_PICKUP_POINT_CODE_CLIENT                => ChronopostPickupPoint::getConfigValue(self::CHRONOPOST_PICKUP_POINT_CODE_CLIENT),
            self::CHRONOPOST_PICKUP_POINT_PASSWORD                   => ChronopostPickupPoint::getConfigValue(self::CHRONOPOST_PICKUP_POINT_PASSWORD),

            /** END */
        ];

        /** Delivery types */
        foreach (self::getDeliveryTypesStatusKeys() as $statusKey) {
            $config[$statusKey] = ChronopostPickupPoint::getConfigValue($statusKey);
        }

        /** Set the local static config value */
        self::$config = $config;
    }

    /**
     * Return the local static config value or the value of a given parameter
     *
     * @param null $parameter
     * @return array|mixed|null
     */
    public static function getConfig($parameter = null)
    {
        /** Check if the local config value is set, and set it if it's not */
        if (null === self::$config) {
            self::setConfig();
        }

        /** Return the value of the config parameter given, or null if it wasn't set */
        if (null !== $parameter) {
            return (isset(self::$config[$parameter])) ? self::$config[$parameter] : null;
        }

        /** Return the local static config value */
        return self::$config;
    }

    /** Status keys of the delivery types.
     *  @return array
     */
    public static function getDeliveryTypesStatusKeys()
    {
        $statusKeys = [];

        foreach (self::CHRONOPOST_PICKUP_POINT_DELIVERY_CODES as $name => $code) {
            $statusKeys[$name] = 'chronopost_pickup_point_delivery_' . strtolower($name) . '_status';
        }

        return $statusKeys;
    }
}