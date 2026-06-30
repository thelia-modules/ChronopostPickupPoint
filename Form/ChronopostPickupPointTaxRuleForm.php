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

namespace ChronopostPickupPoint\Form;

use ChronopostPickupPoint\ChronopostPickupPoint;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Model\LangQuery;
use Thelia\Model\TaxRuleI18nQuery;

class ChronopostPickupPointTaxRuleForm extends BaseForm
{
    protected function buildForm(): void
    {
        $this->formBuilder
            ->add('tax_rule_id',
                ChoiceType::class,
                [
                    'data' => (int) ChronopostPickupPoint::getConfigValue(ChronopostPickupPoint::CHRONOPOST_TAX_RULE_ID),
                    'choices' => $this->getTaxRules(),
                    'label' => Translator::getInstance()->trans('Tax Rule', [], ChronopostPickupPoint::DOMAIN_NAME),
                ]
            );
    }

    private function getTaxRules(): array
    {
        $res = [];

        /** @var Request $request */
        $request = $this->request;

        $lang = $request->hasSession() ? $request->getSession()->getAdminEditionLang() : null;
        $lang ??= LangQuery::create()->filterByByDefault(1)->findOne();

        $taxRules = TaxRuleI18nQuery::create()
            ->filterByLocale($lang->getLocale())
            ->find();

        $res[Translator::getInstance()->trans('Default Tax rule', [], ChronopostPickupPoint::DOMAIN_NAME)] = null;

        foreach ($taxRules as $taxRule) {
            $res[$taxRule->getTitle()] = $taxRule->getId();
        }

        return $res;
    }
}
