<?php

namespace ChronopostPickupPoint\Form;


use ChronopostPickupPoint\Config\ChronopostPickupPointConst;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class ChronopostPickupPointConfigurationForm extends BaseForm
{
    protected function buildForm()
    {
        $config = ChronopostPickupPointConst::getConfig();

        $this->formBuilder

            /** Chronopost basic informations */
            ->add(
                ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_CODE_CLIENT,
                "text",
                [
                    'required'      => true,
                    'data'          => $config[ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_CODE_CLIENT],
                    'label'         => Translator::getInstance()->trans("Chronopost client ID"),
                    'label_attr'    => [
                        'for'           => 'title',
                    ],
                    'attr'          => [
                        'placeholder'   => Translator::getInstance()->trans("Your Chronopost client ID"),
                    ],
                ]
            )
            ->add(ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_PASSWORD,
                "text",
                [
                    'required'      => true,
                    'data'          => $config[ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_PASSWORD],
                    'label'         => Translator::getInstance()->trans("Chronopost password"),
                    'label_attr'    => [
                        'for'           => 'title',
                    ],
                    'attr'          => [
                        'placeholder'   => Translator::getInstance()->trans("Your Chronopost password"),
                    ],
                ]
            )
        ;

        /** Delivery types */
        foreach (ChronopostPickupPointConst::getDeliveryTypesStatusKeys() as $deliveryTypeName => $statusKey) {
            $this->formBuilder
                ->add($statusKey,
                    "checkbox",
                    [
                        'required'      => false,
                        'data'          => (bool)$config[$statusKey],
                        'label'         => Translator::getInstance()->trans("\"" . $deliveryTypeName . "\" Delivery (Code : " . ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_DELIVERY_CODES[$deliveryTypeName] . ")"),
                        'label_attr'    => [
                            'for'           => 'title',
                        ],
                    ]
                )
            ;
        }

        /** BUILDFORM END */
    }

    public function getName()
    {
        return "chronopost_pickup_point_configuration_form";
    }
}