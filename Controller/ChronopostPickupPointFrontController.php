<?php

declare(strict_types=1);

namespace ChronopostPickupPoint\Controller;


use ChronopostPickupPoint\Model\ChronopostPickupPointOrderAddress;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Model\CountryQuery;
use Symfony\Component\Routing\Attribute\Route;

/**
 */
#[Route('/chronopost/pickup-point', name: 'chronopost-pickup-point_front')]
class ChronopostPickupPointFrontController extends BaseFrontController
{
    /**
     * @return Response
     * @throws \Propel\Runtime\Exception\PropelException
     */
    #[Route('/save', name: '_save_address', methods: ['GET'])]
    public function saveAddressAction(RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();
        if (null === $request){
            throw new \Exception('Request not found');
        }
        $addr = new ChronopostPickupPointOrderAddress();
        $countryId = CountryQuery::create()->filterByIsoalpha2($request->query->get('country'))->findOne()->getId();

        $addr
            ->setCompany($request->query->get('company'))
            ->setAddress1($request->query->get('addr1'))
            ->setAddress2($request->query->get('addr2'))
            ->setAddress3($request->query->get('addr3'))
            ->setCountryId($countryId)
            ->setZipCode($request->query->get('zip'))
            ->setCity($request->query->get('city'))
            ->save()
        ;

        if ($request->hasSession()) {
            $request->getSession()->set('ChronopostPickupPointId', $addr->getId());
        }

        return $this->jsonResponse($addr->getId(), 200);
    }
}
