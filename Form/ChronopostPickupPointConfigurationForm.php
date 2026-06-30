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

use ChronopostPickupPoint\Config\ChronopostPickupPointConst;
use ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryModeQuery;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Model\LangQuery;

class ChronopostPickupPointConfigurationForm extends BaseForm
{
    protected function buildForm(): void
    {
        $config = ChronopostPickupPointConst::getConfig();

        $this->formBuilder

            /* Chronopost basic information */
            ->add(
                ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_CODE_CLIENT,
                TextType::class,
                [
                    'required' => true,
                    'data' => $config[ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_CODE_CLIENT],
                    'label' => Translator::getInstance()->trans('Chronopost client ID'),
                    'label_attr' => [
                        'for' => 'title',
                    ],
                    'attr' => [
                        'placeholder' => Translator::getInstance()->trans('Your Chronopost client ID'),
                    ],
                ]
            )
            ->add(ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_PASSWORD,
                PasswordType::class,
                [
                    'required' => true,
                    'data' => $config[ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_PASSWORD],
                    'label' => Translator::getInstance()->trans('Chronopost password'),
                    'label_attr' => [
                        'for' => 'title',
                    ],
                    'attr' => [
                        'placeholder' => Translator::getInstance()->trans('Your Chronopost password'),
                    ],
                ]
            )
        ;

        $request = $this->getRequest();
        $lang = $request->hasSession() ? $request->getSession()->get('thelia.current.admin_lang') : null;
        if (null === $lang) {
            $lang = LangQuery::create()
                ->filterByByDefault(1)
                ->findOne();
        }

        /* Delivery types */
        foreach (ChronopostPickupPointConst::getDeliveryTypesStatusKeys() as $deliveryTypeName => $statusKey) {
            $deliveryMode = ChronopostPickupPointDeliveryModeQuery::create()
                ->filterByCode(ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_DELIVERY_CODES[$deliveryTypeName])
                ->findOne();
            $deliveryModeTitle = $deliveryMode ? $deliveryMode->setLocale($lang->getLocale())->getTitle() : $deliveryTypeName;
            $this->formBuilder
                ->add($statusKey,
                    CheckboxType::class,
                    [
                        'required' => false,
                        'data' => (bool) $config[$statusKey],
                        'label' => Translator::getInstance()->trans('"'.$deliveryModeTitle.'" Delivery (Code : '.ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_DELIVERY_CODES[$deliveryTypeName].')'),
                        'label_attr' => [
                            'for' => $statusKey,
                        ],
                    ]
                )
            ;
        }

        /* BUILD FORM END */
    }

    public static function getName(): string
    {
        return 'chronopost_pickup_point_configuration_form';
    }
}
