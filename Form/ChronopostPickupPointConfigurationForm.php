<?php

namespace ChronopostPickupPoint\Form;


use ChronopostPickupPoint\Config\ChronopostPickupPointConst;
use ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryModeQuery;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Model\LangQuery;

class ChronopostPickupPointConfigurationForm extends BaseForm
{
    protected function buildForm()
    {
        $config = ChronopostPickupPointConst::getConfig();

        $this->formBuilder

            /** Chronopost basic informations */
            ->add(
                ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_CODE_CLIENT,
                TextType::class,
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
                TextType::class,
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

        $lang = $this->getRequest()->getSession()->get('thelia.current.admin_lang');
        if (null === $lang) {
            $lang = LangQuery::create()
                ->filterByByDefault(1)
                ->findOne();
        }

        /** Delivery types */
        foreach (ChronopostPickupPointConst::getDeliveryTypesStatusKeys() as $deliveryTypeName => $statusKey) {
            $deliveryMode = ChronopostPickupPointDeliveryModeQuery::create()
                ->filterByCode(ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_DELIVERY_CODES[$deliveryTypeName])
                ->findOne();
            $deliveryModeTitle = $deliveryMode ? $deliveryMode->setLocale($lang->getLocale())->getTitle() : $deliveryTypeName;
            $this->formBuilder
                ->add($statusKey,
                    CheckboxType::class,
                    [
                        'required'      => false,
                        'data'          => (bool)$config[$statusKey],
                        'label'         => Translator::getInstance()->trans("\"" . $deliveryModeTitle . "\" Delivery (Code : " . ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_DELIVERY_CODES[$deliveryTypeName] . ")"),
                        'label_attr'    => [
                            'for'           => 'title',
                        ],
                    ]
                )
            ;
        }

        /** BUILDFORM END */
    }

    public static function getName()
    {
        return "chronopost_pickup_point_configuration_form";
    }
}