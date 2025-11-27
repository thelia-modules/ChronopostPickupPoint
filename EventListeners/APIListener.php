<?php

declare(strict_types=1);

/*
 * This file is part of the Thelia package.
 * http://www.thelia.net
 *
 * (c) OpenStudio <info@thelia.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChronopostPickupPoint\EventListeners;

use ChronopostPickupPoint\ChronopostPickupPoint;
use ChronopostPickupPoint\Config\ChronopostPickupPointConst;
use ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryModeQuery;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Api\Bridge\Propel\Event\DeliveryModuleOptionEvent;
use Thelia\Api\Resource\DeliveryModuleOption;
use Thelia\Api\Resource\DeliveryPickupLocation;
use Thelia\Api\Resource\PickupLocationAddress;
use Thelia\Core\Event\Delivery\PickupLocationEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Translation\Translator;

class APIListener implements EventSubscriberInterface
{
    public function __construct(protected RequestStack $requestStack)
    {
    }

    /**
     * Get the list of delivery types.
     */
    public function getDeliveryModuleOptions(DeliveryModuleOptionEvent $deliveryModuleOptionEvent): void
    {
        if ($deliveryModuleOptionEvent->getModule()->getId() !== ChronopostPickupPoint::getModuleId()) {
            return;
        }

        $activatedDeliveryTypes = ChronopostPickupPoint::getActivatedDeliveryTypes();

        $deliveryModes = ChronopostPickupPointDeliveryModeQuery::create()->find();
        $lang = $this->requestStack->getCurrentRequest()->getSession()->getLang();

        foreach ($deliveryModes as $deliveryMode) {
            if (!\in_array($deliveryMode->getCode(), $activatedDeliveryTypes, false)) {
                continue;
            }

            $isValid = true;
            $orderPostage = null;

            try {
                $module = new ChronopostPickupPoint();
                $country = $deliveryModuleOptionEvent->getCountry();

                $orderPostage = $module->getMinPostage(
                    $country,
                    $deliveryModuleOptionEvent->getCart()->getWeight(),
                    $deliveryModuleOptionEvent->getCart()->getTaxedAmount($country),
                    $deliveryMode->getCode(),
                    $lang->getLocale()
                );
            } catch (\Exception $exception) {
                $isValid = false;
            }

            $minimumDeliveryDate = ''; // TODO (with a const array code => timeToDeliver to calculate delivery date from day of order)
            $maximumDeliveryDate = ''; // TODO (with a const array code => timeToDeliver to calculate delivery date from day of order)

            $deliveryModuleOption = new DeliveryModuleOption();
            $deliveryModuleOption
                ->setCode($deliveryMode->getCode())
                ->setValid($isValid)
                ->setTitle($deliveryMode->setLocale($lang->getLocale())->getTitle())
                ->setImage('')
                ->setMinimumDeliveryDate($minimumDeliveryDate)
                ->setMaximumDeliveryDate($maximumDeliveryDate)
                ->setPostage(($orderPostage) ? $orderPostage->getAmount() : 0)
                ->setPostageTax(($orderPostage) ? $orderPostage->getAmountTax() : 0)
                ->setPostageUntaxed(($orderPostage) ? $orderPostage->getAmount() - $orderPostage->getAmountTax() : 0)
            ;

            $deliveryModuleOptionEvent->appendDeliveryModuleOptions($deliveryModuleOption);
        }
    }

    /**
     * Calls the Chronopost API and returns a response containing the informations of the relay points found.
     *
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
            'accountNumber' => $config[ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_CODE_CLIENT],
            'password' => $config[ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_PASSWORD],
            'adress' => $pickupLocationEvent->getAddress(),
            'zipCode' => $pickupLocationEvent->getZipCode(),
            'city' => $pickupLocationEvent->getCity(),
            'countryCode' => $countryCode,
            'type' => 'T', /* Mandatory. Relay type to search for. T == All. */
            'productCode' => '58', /* Type of 'Chronopost Product' (aka delivery method chosen). 58 == Standard relay point delivery in France */
            'service' => 'T', /* Mandatory. Type of service requested. Unused as of 23/06/2020. T == All. */
            'weight' => $pickupLocationEvent->getOrderWeight(),
            'shippingDate' => $tomorrow,
            'maxPointChronopost' => $pickupLocationEvent->getMaxRelays() > 25 ? 25 : $pickupLocationEvent->getMaxRelays(),
            'maxDistanceSearch' => (int) round((float) $pickupLocationEvent->getRadius() / 1000),
            'holidayTolerant' => '1',
            'language' => 'FR',
            'version' => '2.0',
        ];

        /** Send informations to the Chronopost API */
        $soapClient = new \SoapClient(ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_RELAY_SEARCH_SERVICE_WSDL, ['trace' => 1, 'exception' => 1]);
        $response = $soapClient->__soapCall('recherchePointChronopostInterParService', [$APIData]);

        if (0 != $response->return->errorCode) {
            throw new \Exception($response->return->errorMessage);
        }

        return property_exists($response->return, 'listePointRelais') ? $response->return->listePointRelais : null;
    }

    /**
     * Creates and returns a new location address.
     */
    protected function createPickupLocationAddressFromResponse($response): PickupLocationAddress
    {
        /** We create the new location address */
        $pickupLocationAddress = new PickupLocationAddress();

        /* We set the differents properties of the location address */
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
            ->setIsDefault(false)
            ->setLabel('')
            ->setAdditionalData([])
        ;

        return $pickupLocationAddress;
    }

    /**
     * Creates then returns a location from a response of the WebService.     *
     * @throws \Exception
     */
    protected function createPickupLocationFromResponse($response): DeliveryPickupLocation
    {
        /** We create the new location */
        $pickupLocation = new DeliveryPickupLocation();

        /* We set the differents properties of the location */
        $pickupLocation
            ->setId($response->identifiant)
            ->setTitle($response->nom)
            ->setAddress($this->createPickupLocationAddressFromResponse($response))
            ->setLatitude((float) $response->coordGeolocalisationLatitude)
            ->setLongitude((float) $response->coordGeolocalisationLongitude)
            ->setModuleId(ChronopostPickupPoint::getModuleId())
        ;

        /* We set the opening hours separately since we got them as an array */
        foreach ($response->listeHoraireOuverture as $horaire) {
            if (!property_exists($horaire, 'horairesAsString')) {
                continue;
            }
            $pickupLocation->setOpeningHours($horaire->jour - 1, $horaire->horairesAsString);
        }

        return $pickupLocation;
    }

    /**
     * Get the list of locations (relay points).
     *
     * @throws \Exception
     */
    public function getPickupLocations(PickupLocationEvent $pickupLocationEvent): void
    {
        if (null !== $moduleIds = $pickupLocationEvent->getModuleIds()) {
            if (!\in_array(ChronopostPickupPoint::getModuleId(), $moduleIds, false)) {
                return;
            }
        }

        /** The @var array $responses from the Webservice that calls the module API */
        $responses = $this->callWebService($pickupLocationEvent);

        if (null === $responses) {
            throw new \Exception(Translator::getInstance()->trans('No pickup points were found for these informations. Maybe try with a more precise request.'));
        }

        foreach ($responses as $response) {
            /* For each response, we append a new location to the list */
            $pickupLocationEvent->appendLocation($this->createPickupLocationFromResponse($response));
        }
    }

    public static function getSubscribedEvents(): array
    {
        $listenedEvents = [];

        if (class_exists(DeliveryModuleOptionEvent::class)) {
            $listenedEvents[TheliaEvents::MODULE_DELIVERY_GET_OPTIONS] = ['getDeliveryModuleOptions', 129];
        }
        $listenedEvents[TheliaEvents::MODULE_DELIVERY_GET_PICKUP_LOCATIONS] = ['getPickupLocations', 135];

        return $listenedEvents;
    }
}
