<?php

declare(strict_types=1);

namespace ChronopostPickupPoint\Hook;

use ChronopostPickupPoint\ChronopostPickupPoint;
use ChronopostPickupPoint\Config\ChronopostPickupPointConst;
use ChronopostPickupPoint\Form\ChronopostPickupPointConfigurationForm;
use ChronopostPickupPoint\Form\ChronopostPickupPointFreeShippingForm;
use ChronopostPickupPoint\Form\ChronopostPickupPointTaxRuleForm;
use ChronopostPickupPoint\Model\ChronopostPickupPointAreaFreeshippingQuery;
use ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryModeQuery;
use ChronopostPickupPoint\Model\ChronopostPickupPointPriceQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Form\TheliaFormFactory;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Template\Parser\ParserResolver;
use Thelia\Model\AreaQuery;
use Thelia\Model\CurrencyQuery;
use Thelia\Model\LangQuery;

class BackHook extends BaseHook
{
    public function __construct(
        private readonly TheliaFormFactory $formFactory,
        ?EventDispatcherInterface $dispatcher = null,
        ?ParserResolver $parserResolver = null,
    ) {
        parent::__construct($dispatcher, $parserResolver);
    }

    public static function getSubscribedHooks(): array
    {
        return [
            'module.configuration' => [
                ['type' => 'back', 'method' => 'onModuleConfiguration'],
            ],
            'module.config-js' => [
                ['type' => 'back', 'method' => 'onModuleConfigJs'],
            ],
        ];
    }

    public function onModuleConfiguration(HookRenderEvent $event): void
    {
        $locale = $this->getEditionLocale();
        $moduleId = ChronopostPickupPoint::getModuleId();

        $configForm = $this->formFactory
            ->createForm(ChronopostPickupPointConfigurationForm::getName())
            ->createView()
            ->getView();

        $taxRuleForm = $this->formFactory
            ->createForm(ChronopostPickupPointTaxRuleForm::getName())
            ->createView()
            ->getView();

        $freeShippingForm = $this->formFactory
            ->createForm(ChronopostPickupPointFreeShippingForm::getName())
            ->createView()
            ->getView();

        $event->add($this->render('ChronopostPickupPoint/ChronopostPickupPointConfig.html.twig', [
            'config_form' => $configForm,
            'tax_rule_form' => $taxRuleForm,
            'free_shipping_form' => $freeShippingForm,
            'delivery_status_keys' => ChronopostPickupPointConst::getDeliveryTypesStatusKeys(),
            'delivery_modes' => $this->getDeliveryModes($locale),
            'areas' => $this->getAreas($moduleId, $locale),
            'currency_symbol' => $this->getCurrencySymbol(),
            'module_id' => $moduleId,
        ]));
    }

    public function onModuleConfigJs(HookRenderEvent $event): void
    {
        $event->add($this->render('ChronopostPickupPoint/module-config-js.html.twig'));
    }

    private function getEditionLocale(): string
    {
        $request = $this->getRequest();
        $session = $request?->getSession();

        if ($session !== null) {
            $lang = $session->get('thelia.admin.edition.lang')
                ?? $session->get('thelia.current.admin_lang');
            if ($lang !== null && method_exists($lang, 'getLocale')) {
                return $lang->getLocale();
            }
        }

        $default = LangQuery::create()->filterByByDefault(1)->findOne();

        return $default?->getLocale() ?? 'en_US';
    }

    /**
     * Reproduces the chronopost_pickup_point_delivery_mode loop:
     * the delivery modes enabled in the module configuration.
     *
     * @return array<int, array<string, mixed>>
     */
    private function getDeliveryModes(string $locale): array
    {
        $config = ChronopostPickupPointConst::getConfig();

        $enabledCodes = [];
        foreach (ChronopostPickupPointConst::getDeliveryTypesStatusKeys() as $name => $statusKey) {
            if (!empty($config[$statusKey])) {
                $enabledCodes[] = ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_DELIVERY_CODES[$name];
            }
        }

        if ($enabledCodes === []) {
            return [];
        }

        $modes = ChronopostPickupPointDeliveryModeQuery::create()
            ->filterByCode($enabledCodes, Criteria::IN)
            ->find();

        $result = [];
        foreach ($modes as $mode) {
            $result[] = [
                'id' => $mode->getId(),
                'title' => $mode->setLocale($locale)->getTitle(),
                'code' => $mode->getCode(),
                'freeshipping_active' => $mode->getFreeshippingActive(),
                'freeshipping_from' => $mode->getFreeshippingFrom(),
            ];
        }

        return $result;
    }

    /**
     * Reproduces the area loop scoped to this delivery module.
     *
     * @return array<int, array<string, mixed>>
     */
    private function getAreas(int $moduleId, string $locale): array
    {
        $areas = AreaQuery::create()
            ->useAreaDeliveryModuleQuery()
                ->filterByDeliveryModuleId([$moduleId], Criteria::IN)
            ->endUse()
            ->find();

        $result = [];
        foreach ($areas as $area) {
            $result[] = [
                'id' => $area->getId(),
                'name' => $area->getName(),
                'freeshipping' => $this->getAreaFreeshipping($area->getId()),
                'prices' => $this->getAreaPrices($area->getId()),
            ];
        }

        return $result;
    }

    /**
     * Reproduces chronopost_pickup_point_area_freeshipping_loop, keyed by delivery mode id.
     *
     * @return array<int, string|null> deliveryModeId => cart amount
     */
    private function getAreaFreeshipping(int $areaId): array
    {
        $rows = ChronopostPickupPointAreaFreeshippingQuery::create()
            ->filterByAreaId($areaId)
            ->find();

        $result = [];
        foreach ($rows as $row) {
            $result[(int) $row->getDeliveryModeId()] = $row->getCartAmount();
        }

        return $result;
    }

    /**
     * Reproduces chronopost_pickup_point_loop (price slices), keyed by delivery mode id.
     *
     * @return array<int, array<int, array<string, mixed>>>
     */
    private function getAreaPrices(int $areaId): array
    {
        $prices = ChronopostPickupPointPriceQuery::create()
            ->filterByAreaId($areaId)
            ->orderByWeightMax()
            ->find();

        $result = [];
        foreach ($prices as $price) {
            $result[(int) $price->getDeliveryModeId()][] = [
                'slice_id' => $price->getId(),
                'max_weight' => $price->getWeightMax(),
                'max_price' => $price->getPriceMax(),
                'price' => $price->getPrice(),
            ];
        }

        return $result;
    }

    private function getCurrencySymbol(): string
    {
        $currency = CurrencyQuery::create()->findOneByByDefault(true);

        return $currency?->getSymbol() ?? '';
    }
}
