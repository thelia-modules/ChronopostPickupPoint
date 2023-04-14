<?php

namespace ChronopostPickupPoint\Controller;


use ChronopostPickupPoint\Form\ChronopostPickupPointFreeShippingForm;
use ChronopostPickupPoint\Model\ChronopostPickupPointAreaFreeshipping;
use ChronopostPickupPoint\Model\ChronopostPickupPointAreaFreeshippingQuery;
use ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryModeQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Model\AreaQuery;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/module/chronopost-pickup-point", name="chronopost-pickup-point")
 */
class ChronopostPickupPointFreeShippingController extends BaseAdminController
{
    /**
     * Toggle free shipping for the delivery type being edited.
     *
     * @Route("/freeshipping", name="_freeshipping", methods="POST")
     * @return mixed|null|Response|static
     */
    public function toggleFreeShippingActivation()
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('ChronopostPickupPoint'), AccessManager::UPDATE)) {
            return $response;
        }

        $form = $this->createForm(ChronopostPickupPointFreeShippingForm::getName());
        $response = null;

        try {
            $vform = $this->validateForm($form);
            $freeshipping = $vform->get('freeshipping')->getData();
            $deliveryModeId = $vform->get('delivery_mode')->getData();

            $deliveryMode = ChronopostPickupPointDeliveryModeQuery::create()->findOneById($deliveryModeId);
            $deliveryMode
                ->setFreeshippingActive($freeshipping)
                ->save();
            $response = Response::create('');
        } catch (\Exception $e) {
            $response = JsonResponse::create(array("error" => $e->getMessage()), 500);
        }

        return $response;
    }

    /**
     * @Route("/freeshipping_from", name="_freeshipping_from", methods="POST")
     * @return mixed|Response
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setFreeShippingFrom(RequestStack $requestStack)
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('ChronopostPickupPoint'), AccessManager::UPDATE)) {
            return $response;
        }

        $request = $requestStack->getCurrentRequest();
        if (null === $request){
            throw new \Exception('Request not found');
        }


        $data = $request->request;
        $deliveryMode = ChronopostPickupPointDeliveryModeQuery::create()->findOneById($data->get('delivery-mode'));

        $price = $data->get("price") === "" ? null : $data->get("price");

        if ($price < 0) {
            $price = null;
        }
        $deliveryMode->setFreeshippingFrom($price)
            ->save();

        return $this->generateRedirectFromRoute(
            "admin.module.configure",
            array(),
            array(
                'current_tab'=>'prices_slices_tab_' . $data->get('delivery-mode'),
                'module_code'=>"ChronopostPickupPoint",
                '_controller' => 'Thelia\\Controller\\Admin\\ModuleController::configureAction',
                'price_error_id' => null,
                'price_error' => null
            )
        );
    }

    /**
     * Set free shipping for a given area of the delivery type being edited.
     *
     * @Route("/area_freeshipping", name="_area_freeshipping", methods="POST")
     * @return mixed|null|Response
     */
    public function setAreaFreeShipping(RequestStack $requestStack)
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('ChronopostPickupPoint'), AccessManager::UPDATE)) {
            return $response;
        }

        try {
            $request = $requestStack->getCurrentRequest();
            if (null === $request){
                throw new \Exception('Request not found');
            }
            $data = $request->request;

            $chronopostAreaId = $data->get('area-id');
            $chronopostDeliveryId = $data->get('delivery-mode');
            $cartAmount = $data->get("cart-amount");

            if ($cartAmount < 0 || $cartAmount === '') {
                $cartAmount = null;
            }

            $areaQuery = AreaQuery::create()->findOneById($chronopostAreaId);
            if (null === $areaQuery) {
                return null;
            }

            $deliveryModeQuery = ChronopostPickupPointDeliveryModeQuery::create()->findOneById($chronopostDeliveryId);
            if (null === $deliveryModeQuery) {
                return null;
            }

            $chronopostFreeShipping = new ChronopostPickupPointAreaFreeshipping();
            $chronopostFreeShippingQuery = ChronopostPickupPointAreaFreeshippingQuery::create()
                ->filterByAreaId($chronopostAreaId)
                ->filterByDeliveryModeId($chronopostDeliveryId)
                ->findOne();

            if (null === $chronopostFreeShippingQuery) {
                $chronopostFreeShipping
                    ->setAreaId($chronopostAreaId)
                    ->setDeliveryModeId($chronopostDeliveryId)
                    ->setCartAmount($cartAmount)
                    ->save();
            }

            $cartAmountQuery = ChronopostPickupPointAreaFreeshippingQuery::create()
                ->filterByAreaId($chronopostAreaId)
                ->filterByDeliveryModeId($chronopostDeliveryId)
                ->findOneOrCreate()
                ->setCartAmount($cartAmount)
                ->save();
        } catch (\Exception $e) {

        }

        return $this->generateRedirectFromRoute(
            "admin.module.configure",
            array(),
            array(
                'current_tab' => 'prices_slices_tab_' . $data->get('delivery-mode'),
                'module_code' => "ChronopostPickupPoint",
                '_controller' => 'Thelia\\Controller\\Admin\\ModuleController::configureAction',
                'price_error_id' => null,
                'price_error' => null
            )
        );
    }

}