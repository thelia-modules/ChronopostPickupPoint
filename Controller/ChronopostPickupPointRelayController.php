<?php


namespace ChronopostPickupPoint\Controller;


use ChronopostPickupPoint\Config\ChronopostPickupPointConst;
use ChronopostPickupPoint\Model\ChronopostPickupPointOrderAddress;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Model\CountryQuery;

class ChronopostPickupPointRelayController extends BaseAdminController
{
    public function findByAddress($orderWeight, $address, $zipCode, $city, $countryCode)
    {
        $config = ChronopostPickupPointConst::getConfig();

        $datetime = new \DateTime('tomorrow');
        $tomorrow = $datetime->format('d/m/Y');

        /** START */

        /** SHIPPER INFORMATIONS */
        $APIData = [
            "accountNumber" => $config[ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_CODE_CLIENT],
            "password" => $config[ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_PASSWORD],
            "adress" => $address,
            "zipCode" => $zipCode,
            "city" => $city,
            "countryCode" => $countryCode,
            "type" => 'T',
            "productCode" => '58', //todo : Make this a variable
            "service" => 'T',
            "weight" => $orderWeight,
            "shippingDate" => $tomorrow,
            "maxPointChronopost" => '15',
            "maxDistanceSearch" => '10',
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

        return $response->return->listePointRelais;
    }

    /**
     * @return \Thelia\Core\HttpFoundation\Response
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function saveAddressAction()
    {
        $addr = new ChronopostPickupPointOrderAddress();
        $countryId = CountryQuery::create()->filterByIsoalpha2($this->getRequest()->get('country'))->findOne()->getId();

        $addr
            ->setCompany($this->getRequest()->get('company'))
            ->setAddress1($this->getRequest()->get('addr1'))
            ->setAddress2($this->getRequest()->get('addr2'))
            ->setAddress3($this->getRequest()->get('addr3'))
            ->setCountryId($countryId)
            ->setZipCode($this->getRequest()->get('zip'))
            ->setCity($this->getRequest()->get('city'))
            ->save();

        $this->getRequest()->getSession()->set('ChronopostPickupPointId', $addr->getId());

        return $this->jsonResponse($addr->getId(), 200);
    }
}