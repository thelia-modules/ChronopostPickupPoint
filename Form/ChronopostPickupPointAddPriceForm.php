<?php

namespace ChronopostPickupPoint\Form;

use ChronopostPickupPoint\ChronopostPickupPoint;
use ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryModeQuery;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Model\AreaQuery;

class ChronopostPickupPointAddPriceForm extends BaseForm
{
    protected function buildForm()
    {
        $this->formBuilder
            ->add("area", IntegerType::class, array(
                "constraints" => array(
                    new Constraints\NotBlank(),
                    new Constraints\Callback(
                        array($this, "verifyAreaExist")
                    )
                )
            ))
            ->add("delivery_mode", IntegerType::class, array(
                "constraints" => array(
                    new Constraints\NotBlank(),
                    new Constraints\Callback(
                        array($this, "verifyDeliveryModeExist")
                    )
                )
            ))
            ->add("weight", NumberType::class, array(
                "constraints" => array(
                    new Constraints\NotBlank(),
                    new Constraints\Callback(
                        array($this, "verifyValidWeight")
                    )
                )
            ))
            ->add("price", NumberType::class, array(
                "constraints" => array(
                    new Constraints\NotBlank(),
                    new Constraints\Callback(
                        array($this, "verifyValidPrice")
                    )
                )
            ))
            ->add("franco", "number", array())
        ;
    }

    public function verifyAreaExist($value, ExecutionContextInterface $context)
    {
        $area = AreaQuery::create()->findPk($value);
        if (null === $area) {
            $context->addViolation(Translator::getInstance()->trans("This area doesn't exists.", [], ChronopostPickupPoint::DOMAIN_NAME));
        }
    }

    public function verifyDeliveryModeExist($value, ExecutionContextInterface $context)
    {
        $mode = ChronopostPickupPointDeliveryModeQuery::create()->findPk($value);
        if (null === $mode) {
            $context->addViolation(Translator::getInstance()->trans("This delivery mode doesn't exists.", [], ChronopostPickupPoint::DOMAIN_NAME));
        }
    }

    public function verifyValidWeight($value, ExecutionContextInterface $context)
    {
        if (!preg_match("#^\d+\.?\d*$#", $value)) {
            $context->addViolation(Translator::getInstance()->trans("The weight value is not valid.", [], ChronopostPickupPoint::DOMAIN_NAME));
        }

        if ($value < 0) {
            $context->addViolation(Translator::getInstance()->trans("The weight value must be superior to 0.", [], ChronopostPickupPoint::DOMAIN_NAME));
        }
    }

    public function verifyValidPrice($value, ExecutionContextInterface $context)
    {
        if (!preg_match("#^\d+\.?\d*$#", $value)) {
            $context->addViolation(Translator::getInstance()->trans("The price value is not valid.", [], ChronopostPickupPoint::DOMAIN_NAME));
        }
    }

    public static function getName()
    {
        return "chronopost_pickup_point_price_create";
    }
}