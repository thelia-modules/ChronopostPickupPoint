<?php

declare(strict_types=1);

namespace ChronopostPickupPoint\Form;


use ChronopostPickupPoint\ChronopostPickupPoint;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class ChronopostPickupPointFreeShippingForm extends BaseForm
{
    protected function buildForm(): void
    {
        $this->formBuilder
            ->add(
                "delivery_mode",
                IntegerType::class
            )
            ->add(
                "freeshipping",
                CheckboxType::class,
                [
                    'label' => Translator::getInstance()->trans("Activate free shipping: ", [], ChronopostPickupPoint::DOMAIN_NAME),
                ]
            );
    }

    public static function getName(): string
    {
        return "chronopost_pickup_point_freeshipping";
    }
}
