<?php

namespace ChronopostPickupPoint\Service;

use ChronopostPickupPoint\Model\ChronopostPickupPointOrderAddress;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Model\CountryQuery;

class ChronopostPickupPointService
{
    public function __construct(private readonly Session $session) {}

    public function saveAddress(
        ?string $company,
        ?string $address1,
        ?string $address2 = null,
        ?string $address3 = null,
        string $countryIsoAlpha2,
        string $zipCode,
        string $city,
    ): ChronopostPickupPointOrderAddress {
        $addr = new ChronopostPickupPointOrderAddress();
        $countryId = CountryQuery::create()->filterByIsoalpha2($countryIsoAlpha2)->findOne()->getId();

        $addr
            ->setCompany($company)
            ->setAddress1($address1)
            ->setAddress2($address2)
            ->setAddress3($address3)
            ->setCountryId($countryId)
            ->setZipCode($zipCode)
            ->setCity($city)
            ->save()
        ;

        $this->session->set('ChronopostPickupPointId', $addr->getId());

        return $addr;
    }

}
