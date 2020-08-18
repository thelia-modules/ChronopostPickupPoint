<?php

namespace ChronopostPickupPoint\Controller;


use ChronopostPickupPoint\ChronopostPickupPoint;
use ChronopostPickupPoint\Model\ChronopostPickupPointPrice;
use ChronopostPickupPoint\Model\ChronopostPickupPointPriceQuery;
use Propel\Runtime\Map\TableMap;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;

class ChronopostPickupPointSliceController extends BaseAdminController
{
    /**
     * Save/Create a price slice in the delivery type being edited
     *
     * @return mixed|null|\Thelia\Core\HttpFoundation\Response
     */
    public function saveSliceAction()
    {
        $response = $this->checkAuth([], ['chronopost'], AccessManager::UPDATE);

        if (null !== $response) {
            return $response;
        }

        $this->checkXmlHttpRequest();

        $responseData = [
            "sucess"    => false,
            "message"   => '',
            "slice"     => null,
        ];

        $messages = [];
        $response = null;

        try {
            $requestData = $this->getRequest()->request;

            if (0 !== $id = (int)($requestData->get('id', 0))) {
                $slice = ChronopostPickupPointPriceQuery::create()->findPk($id);
            } else {
                $slice = new ChronopostPickupPointPrice();
            }

            if (0 !== $areaId = (int)($requestData->get('area', 0))) {
                $slice->setAreaId($areaId);
            } else {
                $messages[] = $this->getTranslator()->trans(
                    "The area is not valid",
                    [],
                    ChronopostPickupPoint::DOMAIN_NAME
                );
            }

            if (0 !== $deliveryMode = (int)($requestData->get("deliveryModeId", 0))) {
                $slice->setDeliveryModeId($deliveryMode);
            } else {
                $messages[] = $this->getTranslator()->trans(
                    "The delivery type is not valid",
                    [],
                    ChronopostPickupPoint::DOMAIN_NAME
                );
            }

            $requestPriceMax = $requestData->get('priceMax', null);
            $requestWeightMax = $requestData->get('weightMax', null);

            if (empty($requestPriceMax) && empty($requestWeightMax)) {
                $messages[] = $this->getTranslator()->trans(
                    'You must specify at least a price max or a weight max value.',
                    [],
                    ChronopostPickupPoint::DOMAIN_NAME
                );
            } else {
                if (!empty($requestPriceMax)) {
                    $priceMax = $this->getFloatVal($requestPriceMax);
                    if (0 < $priceMax) {
                        $slice->setPriceMax($priceMax);
                    } else {
                        $messages[] = $this->getTranslator()->trans(
                            'The price max value is not valid',
                            [],
                            ChronopostPickupPoint::DOMAIN_NAME
                        );
                    }
                } else {
                    $slice->setPriceMax(null);
                }

                if (!empty($requestWeightMax)) {
                    $weightMax = $this->getFloatVal($requestWeightMax);
                    if (0 < $weightMax) {
                        $slice->setWeightMax($weightMax);
                    } else {
                        $messages[] = $this->getTranslator()->trans(
                            'The weight max value is not valid',
                            [],
                            ChronopostPickupPoint::DOMAIN_NAME
                        );
                    }
                } else {
                    $slice->setWeightMax(null);
                }
            }

            $price = $this->getFloatVal($requestData->get('price', 0));
            if (0 <= $price) {
                $slice->setPrice($price);
            } else {
                $messages[] = $this->getTranslator()->trans(
                    'The price value is not valid',
                    [],
                    ChronopostPickupPoint::DOMAIN_NAME
                );
            }

            if (0 === count($messages)) {
                $slice->save();
                $messages[] = $this->getTranslator()->trans(
                    'Your slice has been saved',
                    [],
                    ChronopostPickupPoint::DOMAIN_NAME
                );

                $responseData['success'] = true;
                $responseData['slice'] = $slice->toArray(TableMap::TYPE_STUDLYPHPNAME);
            }

        } catch (\Exception $e) {
            $message[] = $e->getMessage();
        }

        $responseData['message'] = $messages;

        return $this->jsonResponse(json_encode($responseData));
    }

    /**
     * @param $val
     * @param int $default
     * @return float|int|mixed
     */
    protected function getFloatVal($val, $default = -1)
    {
        if (preg_match("#^([0-9\.,]+)$#", $val, $match)) {
            $val = $match[0];
            if (strstr($val, ",")) {
                $val = str_replace(".", "", $val);
                $val = str_replace(",", ".", $val);
            }
            $val = (float)($val);

            return $val;
        }

        return $default;
    }

    /**
     * Delete a price slice in the delivery type being edited
     *
     * @return mixed|null|\Thelia\Core\HttpFoundation\Response
     */
    public function deleteSliceAction()
    {
        $response = $this->checkAuth([], ['chronopost'], AccessManager::DELETE);

        if (null !== $response) {
            return $response;
        }

        $this->checkXmlHttpRequest();

        $responseData = [
            "success" => false,
            "message" => '',
            "slice" => null
        ];

        $response = null;

        try {
            $requestData = $this->getRequest()->request;

            if (0 !== $id = (int)($requestData->get('id', 0))) {
                $slice = ChronopostPickupPointPriceQuery::create()->findPk($id);
                $slice->delete();
                $responseData['success'] = true;
            } else {
                $responseData['message'] = $this->getTranslator()->trans(
                    'The slice has not been deleted',
                    [],
                    ChronopostPickupPoint::DOMAIN_NAME
                );
            }
        } catch (\Exception $e) {
            $responseData['message'] = $e->getMessage();
        }

        return $this->jsonResponse(json_encode($responseData));
    }

}