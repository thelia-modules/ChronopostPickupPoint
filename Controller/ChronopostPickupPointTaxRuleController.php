<?php

namespace ChronopostPickupPoint\Controller;

use ChronopostPickupPoint\ChronopostPickupPoint;
use ChronopostPickupPoint\Form\ChronopostPickupPointTaxRuleForm;
use Symfony\Component\Routing\Annotation\Route;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Translation\Translator;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Tools\URL;

#[Route('/admin/module/ChronopostPickupPoint/tax_rule', name: 'chronopost_pickup_point_tax_rule_')]
class ChronopostPickupPointTaxRuleController extends BaseAdminController
{
    #[Route('/save', name: 'save')]
    public function saveTaxRule()
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, ChronopostPickupPoint::DOMAIN_NAME, AccessManager::UPDATE)) {
            return $response;
        }

        $taxRuleForm = $this->createForm(ChronopostPickupPointTaxRuleForm::getName());

        $message = false;

        $url = '/admin/module/ChronopostPickupPoint';

        try {
            $form = $this->validateForm($taxRuleForm);

            // Get the form field values
            $data = $form->getData();

            ChronopostPickupPoint::setConfigValue(ChronopostPickupPoint::CHRONOPOST_TAX_RULE_ID, $data["tax_rule_id"]);

        } catch (FormValidationException $ex) {
            $message = $this->createStandardFormValidationErrorMessage($ex);
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
        }

        if ($message !== false) {
            $this->setupFormErrorContext(
                Translator::getInstance()->trans('Error', [], ChronopostPickupPoint::DOMAIN_NAME),
                $message,
                $taxRuleForm,
                $ex
            );
        }

        return $this->generateRedirect(URL::getInstance()->absoluteUrl($url, [ 'current_tab' => 'tax_rule']));
    }
}