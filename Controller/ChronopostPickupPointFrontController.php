<?php


namespace ChronopostPickupPoint\Controller;


use ChronopostPickupPoint\Model\ChronopostPickupPointOrderAddress;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Model\CountryQuery;

class ChronopostPickupPointFrontController extends BaseFrontController
{
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
            ->save()
        ;

        $this->getRequest()->getSession()->set('ChronopostPickupPointId', $addr->getId());

        return $this->jsonResponse($addr->getId(), 200);
    }
}