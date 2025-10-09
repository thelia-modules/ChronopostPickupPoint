<?php

declare(strict_types=1);

/*
 * This file is part of the Thelia package.
 * http://www.thelia.net
 *
 * (c) OpenStudio <info@thelia.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    protected function getArgDefinitions(): ArgumentCollection
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('area_id'),
            Argument::createIntTypeArgument('delivery_mode_id')
        );
    }

    public function buildModelCriteria(): ModelCriteria
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

    public function parseResults(LoopResult $loopResult): LoopResult
    {
        /** @var \ChronopostPickupPointPickupPoint\Model\ChronopostPickupPointAreaFreeshipping $mode */
        foreach ($loopResult->getResultDataCollection() as $mode) {
            $loopResultRow = new LoopResultRow($mode);
            $loopResultRow->set('ID', $mode->getId())
                ->set('AREA_ID', $mode->getAreaId())
                ->set('DELIVERY_MODE_ID', $mode->getDeliveryModeId())
                ->set('CART_AMOUNT', $mode->getCartAmount());
            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
