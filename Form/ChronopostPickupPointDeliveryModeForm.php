<?php

declare(strict_types=1);

namespace ChronopostPickupPoint\Form;

use ChronopostPickupPoint\ChronopostPickupPoint;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class ChronopostPickupPointDeliveryModeForm extends BaseForm
{
    protected function buildForm(): void
    {
        $this->formBuilder
            ->add(
                "delivery_mode_title",
                TextType::class,
                [
                    'label' => Translator::getInstance()->trans('Delivery mode title', [], ChronopostPickupPoint::DOMAIN_NAME),
                    'label_attr' => [
                        'for' => 'delivery_mode_title'
                    ]
                ]
            )
            ->add(
                "delivery_mode_id",
                HiddenType::class
            )
        ;
    }

    public static function getName(): string
    {
        return "chronopost_pickup_point_delivery_mode";
    }
}
