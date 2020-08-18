<?php

namespace ChronopostPickupPoint\Loop;


use ChronopostPickupPoint\Model\ChronopostPickupPointPrice;
use ChronopostPickupPoint\Model\ChronopostPickupPointPriceQuery;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

/**
 * Class ChronopostPickupPointLoop
 * @package ChronopostPickupPoint\Loop
 *
 * @method integer getAreaId
 * @method integer getDeliveryModeId
 */
class ChronopostPickupPointLoop extends BaseLoop implements PropelSearchLoopInterface
{
    /**
     * @return ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('area_id', null, true),
            Argument::createIntTypeArgument('delivery_mode_id', null, true)
        );
    }

    /**
     * @return ChronopostPickupPointPriceQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $areaId = $this->getAreaId();
        $modeId = $this->getDeliveryModeId();

        $areaPrices = ChronopostPickupPointPriceQuery::create()
            ->filterByDeliveryModeId($modeId)
            ->filterByAreaId($areaId)
            ->orderByWeightMax();

        return $areaPrices;
    }

    /**
     * @param LoopResult $loopResult
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var ChronopostPickupPointPrice $price */
        foreach ($loopResult->getResultDataCollection() as $price) {
            $loopResultRow = new LoopResultRow($price);
            $loopResultRow
                ->set("SLICE_ID", $price->getId())
                ->set("MAX_WEIGHT", $price->getWeightMax())
                ->set("MAX_PRICE", $price->getPriceMax())
                ->set("PRICE", $price->getPrice())
                ->set("FRANCO", $price->getFrancoMinPrice())
            ;
            $loopResult->addRow($loopResultRow);
        }
        return $loopResult;
    }
}