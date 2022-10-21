<?php

namespace ChronopostPickupPoint\Form;


use ChronopostHomeDelivery\ChronopostHomeDelivery;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class ChronopostPickupPointDeliveryModeForm extends BaseForm
{
    /**
     * @return null|void
     */
    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                "delivery_mode_title",
                TextType::class,
                [
                    'label' => Translator::getInstance()->trans('Delivery mode title', [], ChronopostHomeDelivery::DOMAIN_NAME),
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

    /**
     * The name of you form. This name must be unique
     *
     * @return string
     */
    public static function getName()
    {
        return "chronopost_pickup_point_delivery_mode";
    }

}