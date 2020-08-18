<?php

namespace ChronopostPickupPoint\Loop;


use ChronopostPickupPoint\Model\ChronopostPickupPointAreaFreeshippingQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

class ChronopostPickupPointAreaFreeshipping extends BaseLoop implements PropelSearchLoopInterface
{
    /**
     * @return ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('area_id'),
            Argument::createIntTypeArgument('delivery_mode_id')
        );
    }

    /**
     * @return ChronopostPickupPointAreaFreeshippingQuery|ModelCriteria
     */
    public function buildModelCriteria()
    {
        $areaId = $this->getAreaId();
        $mode = $this->getDeliveryModeId();

        $modes = ChronopostPickupPointAreaFreeshippingQuery::create();

        if (null !== $mode) {
            $modes->filterByDeliveryModeId($mode);
        }

        if (null !== $areaId) {
            $modes->filterByAreaId($areaId);
        }

        return $modes;
    }

    /**
     * @param LoopResult $loopResult
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var \ChronopostPickupPointPickupPoint\Model\ChronopostPickupPointAreaFreeshipping $mode */
        foreach ($loopResult->getResultDataCollection() as $mode) {
            $loopResultRow = new LoopResultRow($mode);
            $loopResultRow->set("ID", $mode->getId())
                ->set("AREA_ID", $mode->getAreaId())
                ->set("DELIVERY_MODE_ID", $mode->getDeliveryModeId())
                ->set("CART_AMOUNT", $mode->getCartAmount());
            $loopResult->addRow($loopResultRow);
        }
        return $loopResult;

    }
}