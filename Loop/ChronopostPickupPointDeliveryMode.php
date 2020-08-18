<?php

namespace ChronopostPickupPoint\Loop;


use ChronopostPickupPoint\ChronopostPickupPoint;
use ChronopostPickupPoint\Config\ChronopostPickupPointConst;
use ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryModeQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

class ChronopostPickupPointDeliveryMode extends BaseLoop implements PropelSearchLoopInterface
{
    /**
     * Unused
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection();
    }

    /**
     * @return ChronopostPickupPointDeliveryModeQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $config = ChronopostPickupPointConst::getConfig();
        $modes = ChronopostPickupPointDeliveryModeQuery::create();

        $enabledDeliveryTypes = [];
        foreach (ChronopostPickupPointConst::getDeliveryTypesStatusKeys() as $deliveryTypeName => $statusKey) {
            $enabledDeliveryTypes[] = $config[$statusKey] ? ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_DELIVERY_CODES[$deliveryTypeName] : '';
        }

        $modes->filterByCode($enabledDeliveryTypes, Criteria::IN);

        return $modes;
    }

    /**
     * @param LoopResult $loopResult
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var \ChronopostPickupPointPickupPoint\Model\ChronopostPickupPointDeliveryMode $mode */
        foreach ($loopResult->getResultDataCollection() as $mode) {
            $loopResultRow = new LoopResultRow($mode);
            $loopResultRow
                ->set("ID", $mode->getId())
                ->set("TITLE", $mode->getTitle())
                ->set("CODE", $mode->getCode())
                ->set("FREESHIPPING_ACTIVE", $mode->getFreeshippingActive())
                ->set("FREESHIPPING_FROM", $mode->getFreeshippingFrom());
            $loopResult->addRow($loopResultRow);
        }
        return $loopResult;
    }
}