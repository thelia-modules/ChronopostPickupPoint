<?php

namespace ChronopostPickupPoint\Form;


use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class ChronopostPickupPointFreeShippingForm extends BaseForm
{
    /**
     * @return null|void
     */
    protected function buildForm()
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
                    'label'=>Translator::getInstance()->trans("Activate free shipping: ")
                ]
            )
        ;
    }

    /**
     * The name of you form. This name must be unique
     *
     * @return string
     */
    public static function getName()
    {
        return "chronopost_pickup_point_freeshipping";
    }

}