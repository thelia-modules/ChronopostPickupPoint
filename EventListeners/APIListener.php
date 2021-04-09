<?php


namespace ChronopostPickupPoint\EventListeners;


use ChronopostPickupPoint\ChronopostPickupPoint;
use ChronopostPickupPoint\Config\ChronopostPickupPointConst;
use OpenApi\Events\DeliveryModuleOptionEvent;
use OpenApi\Events\OpenApiEvents;
use OpenApi\Model\Api\DeliveryModuleOption;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Delivery\PickupLocationEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Translation\Translator;
use Thelia\Model\CountryArea;
use Thelia\Model\PickupLocation;
use Thelia\Model\PickupLocationAddress;
use Thelia\Module\Exception\DeliveryException;

class APIListener implements EventSubscriberInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Calls the Chronopost API and returns a response containing the informations of the relay points found
     *
     * @param PickupLocationEvent $pickupLocationEvent
     * @return mixed
     * @throws \SoapFault
     */
    protected function callWebService(PickupLocationEvent $pickupLocationEvent)
    {
        $config = ChronopostPickupPointConst::getConfig();

        $datetime = new \DateTime('tomorrow');
        $tomorrow = $datetime->format('d/m/Y');

        $countryCode = '';

        if ($country = $pickupLocationEvent->getCountry()) {
            $countryCode = $country->getIsoalpha2();
        }

        /** START */

        /** SHIPPER INFORMATIONS */
        $APIData = [
            "accountNumber" => $config[ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_CODE_CLIENT],
            "password" => $config[ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_PASSWORD],
            "adress" => $pickupLocationEvent->getAddress(),
            "zipCode" => $pickupLocationEvent->getZipCode(),
            "city" => $pickupLocationEvent->getCity(),
            "countryCode" => $countryCode,
            "type" => 'T', /** Mandatory. Relay type to search for. T == All. */
            "productCode" => '58', /** Type of 'Chronopost Product' (aka delivery method chosen). 58 == Standard relay point delivery in France */
            "service" => 'T', /** Mandatory. Type of service requested. Unused as of 23/06/2020. T == All. */
            "weight" => $pickupLocationEvent->getOrderWeight(),
            "shippingDate" => $tomorrow,
            "maxPointChronopost" => $pickupLocationEvent->getMaxRelays() > 25 ? 25 : $pickupLocationEvent->getMaxRelays(),
            "maxDistanceSearch" => (int)round(((float)$pickupLocationEvent->getRadius() / 1000)),
            "holidayTolerant" => '1',
            "language" => 'FR',
            "version" => '2.0',
        ];

        /** Send informations to the Chronopost API */
        $soapClient = new \SoapClient(ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_RELAY_SEARCH_SERVICE_WSDL, array("trace" => 1, "exception" => 1));
        $response = $soapClient->__soapCall('recherchePointChronopostInterParService', [$APIData]);

        if (0 != $response->return->errorCode) {
            throw new \Exception($response->return->errorMessage);
        }

        return property_exists($response->return, 'listePointRelais') ? $response->return->listePointRelais : null;
    }

    /**
     * Creates and returns a new location address
     *
     * @param $response
     * @return PickupLocationAddress
     */
    protected function createPickupLocationAddressFromResponse($response)
    {
        /** We create the new location address */
        $pickupLocationAddress = new PickupLocationAddress();

        /** We set the differents properties of the location address */
        $pickupLocationAddress
            ->setId($response->identifiant)
            ->setTitle($response->nom)
            ->setAddress1($response->adresse1)
            ->setAddress2($response->adresse2)
            ->setAddress3($response->adresse3)
            ->setCity($response->localite)
            ->setZipCode($response->codePostal)
            ->setPhoneNumber('')
            ->setCellphoneNumber('')
            ->setCompany($response->nom)
            ->setCountryCode($response->codePays)
            ->setFirstName('')
            ->setLastName('')
            ->setIsDefault(0)
            ->setLabel('')
            ->setAdditionalData([])
        ;

        return $pickupLocationAddress;
    }

    /**
     * Creates then returns a location from a response of the WebService
     *
     * @param $response
     * @return PickupLocation
     * @throws \Exception
     */
    protected function createPickupLocationFromResponse($response)
    {
        /** We create the new location */
        $pickupLocation = new PickupLocation();

        /** We set the differents properties of the location */
        $pickupLocation
            ->setId($response->identifiant)
            ->setTitle($response->nom)
            ->setAddress($this->createPickupLocationAddressFromResponse($response))
            ->setLatitude($response->coordGeolocalisationLatitude)
            ->setLongitude($response->coordGeolocalisationLongitude)
            ->setModuleId(ChronopostPickupPoint::getModuleId())
        ;


        /** We set the opening hours separately since we got them as an array */
        foreach ($response->listeHoraireOuverture as $horaire) {
            if (!property_exists($horaire, 'horairesAsString')) {
                continue ;
            }
            $pickupLocation->setOpeningHours(($horaire->jour - 1), $horaire->horairesAsString);
        }

        return $pickupLocation;
    }

    /**
     * Get the list of locations (relay points)
     *
     * @param PickupLocationEvent $pickupLocationEvent
     * @throws \Exception
     */
    public function getPickupLocations(PickupLocationEvent $pickupLocationEvent)
    {
        if (null !== $moduleIds = $pickupLocationEvent->getModuleIds()) {
            if (!in_array(ChronopostPickupPoint::getModuleId(), $moduleIds, false)) {
                return ;
            }
        }

        /** The @var array $responses from the Webservice that calls the module API */
        $responses = $this->callWebService($pickupLocationEvent);

        if (null === $responses) {
            throw new \Exception(Translator::getInstance()->trans('No pickup points were found for these informations. Maybe try with a more precise request.'));
        }

        foreach ($responses as $response) {
            /** For each response, we append a new location to the list */
            $pickupLocationEvent->appendLocation($this->createPickupLocationFromResponse($response));
        }
    }

    /**
     * Get the list of delivery types
     *
     * @param DeliveryModuleOptionEvent $deliveryModuleOptionEvent
     */
    public function getDeliveryModuleOptions(DeliveryModuleOptionEvent $deliveryModuleOptionEvent)
    {
        if ($deliveryModuleOptionEvent->getModule()->getId() !== ChronopostPickupPoint::getModuleId()) {
            return ;
        }

        $activatedDeliveryTypes = ChronopostPickupPoint::getActivatedDeliveryTypes();

        foreach (ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_DELIVERY_CODES as $name => $code) {
            if (!in_array($code, $activatedDeliveryTypes, false)) {
                continue ;
            }

            $isValid = true;
            $postage = null;
            $postageTax = null;

            try {
                $module = new ChronopostPickupPoint();
                $country = $deliveryModuleOptionEvent->getCountry();

                if (empty($module->getAllAreasForCountry($country))) {
                    throw new DeliveryException(Translator::getInstance()->trans("Your delivery country is not covered by Chronopost"));
                }

                $countryAreas = $country->getCountryAreas();
                $areasArray = [];

                /** @var CountryArea $countryArea */
                foreach ($countryAreas as $countryArea) {
                    $areasArray[] = $countryArea->getAreaId();
                }

                $postage = $module->getMinPostage(
                    $areasArray,
                    $deliveryModuleOptionEvent->getCart()->getWeight(),
                    $deliveryModuleOptionEvent->getCart()->getTaxedAmount($country),
                    $code
                );

                $postageTax = 0; //TODO
            } catch (\Exception $exception) {
                $isValid = false;
            }

            $minimumDeliveryDate = ''; // TODO (with a const array code => timeToDeliver to calculate delivery date from day of order)
            $maximumDeliveryDate = ''; // TODO (with a const array code => timeToDeliver to calculate delivery date from day of order)

            /** @var DeliveryModuleOption $deliveryModuleOption */
            $deliveryModuleOption = ($this->container->get('open_api.model.factory'))->buildModel('DeliveryModuleOption');
            $deliveryModuleOption
                ->setCode($code)
                ->setValid($isValid)
                ->setTitle($name)
                ->setImage('')
                ->setMinimumDeliveryDate($minimumDeliveryDate)
                ->setMaximumDeliveryDate($maximumDeliveryDate)
                ->setPostage($postage)
                ->setPostageTax($postageTax)
                ->setPostageUntaxed($postage - $postageTax)
            ;

            $deliveryModuleOptionEvent->appendDeliveryModuleOptions($deliveryModuleOption);
        }
    }

    public static function getSubscribedEvents()
    {
        $listenedEvents = [];

        /** Check for old versions of Thelia where the events used by the API didn't exists */
        if (class_exists(PickupLocation::class)) {
            $listenedEvents[TheliaEvents::MODULE_DELIVERY_GET_PICKUP_LOCATIONS] = array("getPickupLocations", 135);
        }
        if (class_exists(DeliveryModuleOptionEvent::class)) {
            $listenedEvents[OpenApiEvents::MODULE_DELIVERY_GET_OPTIONS] = array("getDeliveryModuleOptions", 130);
        }

        return $listenedEvents;
    }
}