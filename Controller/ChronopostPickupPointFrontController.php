<?php


namespace ChronopostPickupPoint\Controller;


use ChronopostPickupPoint\Model\ChronopostPickupPointOrderAddress;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Model\CountryQuery;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/chronopost/pickup-point", name="chronopost-pickup-point_front")
 */
class ChronopostPickupPointFrontController extends BaseFrontController
{
    /**
     * @Route("/save", name="_save_address", methods="GET")
     * @return \Thelia\Core\HttpFoundation\Response
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function saveAddressAction(RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();
        if (null === $request){
            throw new \Exception('Request not found');
        }
        $addr = new ChronopostPickupPointOrderAddress();
        $countryId = CountryQuery::create()->filterByIsoalpha2($this->getRequest()->get('country'))->findOne()->getId();

        $addr
            ->setCompany($request->get('company'))
            ->setAddress1($request->get('addr1'))
            ->setAddress2($request->get('addr2'))
            ->setAddress3($request->get('addr3'))
            ->setCountryId($countryId)
            ->setZipCode($request->get('zip'))
            ->setCity($request->get('city'))
            ->save()
        ;

        $request->getSession()->set('ChronopostPickupPointId', $addr->getId());

        return $this->jsonResponse($addr->getId(), 200);
    }
}