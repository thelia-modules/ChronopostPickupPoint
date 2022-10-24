<?php

namespace ChronopostPickupPoint\Controller;


use ChronopostPickupPoint\ChronopostPickupPoint;
use ChronopostPickupPoint\Config\ChronopostPickupPointConst;
use ChronopostPickupPoint\Form\ChronopostPickupPointConfigurationForm;
use ChronopostPickupPoint\Form\ChronopostPickupPointDeliveryModeForm;
use ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryModeQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Translation\Translator;
use Symfony\Component\Routing\Annotation\Route;
use Thelia\Model\LangQuery;

/**
 * @Route("/admin/module/ChronopostPickupPoint", name="ChronopostPickupPoint")
 */
class ChronopostPickupPointBackOfficeController extends BaseAdminController
{
    /**
     * Render the module config page
     *
     * @Route("/config", name="_config", methods="POST")
     * @return \Thelia\Core\HttpFoundation\Response
     */
    /*public function viewAction($tab)
    {
        return $this->render(
            'module-configure',
            [
                'module_code' => 'ChronopostPickupPoint',
                'current_tab' => $tab,
            ]
        );
    }*/

    /**
     * @Route("/saveLabel", name="_saveLabel", methods="POST")
     */
    public function saveLabel(RequestStack $requestStack)
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], 'ChronopostPickupPoint', AccessManager::UPDATE)) {
            return $response;
        }

        $request = $requestStack->getCurrentRequest();
        if (null === $request){
            throw new \Exception('Request not found');
        }

        $labelNbr = $request->get("labelNbr");
        $labelDir = $request->get("labelDir");

        $file = $labelDir .'/'. $labelNbr;

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
        } else {
            return $this->viewAction('export');
            // todo : Error message
        }

        return $this->generateSuccessRedirect();
    }

    /**
     * Save configuration form - Chronopost informations
     *
     * @return mixed|null|\Symfony\Component\HttpFoundation\Response|\Thelia\Core\HttpFoundation\Response
     * @Route("/config", name="_config", methods="POST")
     */
    public function saveAction()
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], 'ChronopostPickupPoint', AccessManager::UPDATE)) {
            return $response;
        }

        $form = $this->createForm(ChronopostPickupPointConfigurationForm::getName());

        try {
            $data = $this->validateForm($form)->getData();

            /** Basic informations */
            ChronopostPickupPoint::setConfigValue(ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_CODE_CLIENT, $data[ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_CODE_CLIENT]);
            ChronopostPickupPoint::setConfigValue(ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_PASSWORD, $data[ChronopostPickupPointConst::CHRONOPOST_PICKUP_POINT_PASSWORD]);

            /** Delivery types */
            foreach (ChronopostPickupPointConst::getDeliveryTypesStatusKeys() as $statusKey) {
                ChronopostPickupPoint::setConfigValue($statusKey, $data[$statusKey]);
            }

        } catch (\Exception $e) {
            $this->setupFormErrorContext(
                Translator::getInstance()->trans(
                    "Error",
                    [],
                    ChronopostPickupPoint::DOMAIN_NAME
                ),
                $e->getMessage(),
                $form
            );

            return $this->viewAction('configure');
        }

        return $this->generateSuccessRedirect($form);
    }

    /**
     * @Route("/delivery-mode", name="_delivery_mode", methods="POST")
     */
    public function updateDeliveryModeTitle(Request $request)
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], 'ChronopostHomeDelivery', AccessManager::UPDATE)) {
            return $response;
        }

        $form = $this->createForm(ChronopostPickupPointDeliveryModeForm::getName());

        try {
            $data = $this->validateForm($form)->getData();

            $deliveryMode = ChronopostPickupPointDeliveryModeQuery::create()->findPk($data['delivery_mode_id']);
            $lang = $request->getSession()->get('thelia.admin.edition.lang');
            if ($lang === null) {
                $lang = LangQuery::create()->filterByByDefault(1)->findOne();
            }

            $deliveryMode
                ->setLocale($lang->getLocale())
                ->setTitle($data['delivery_mode_title'])
                ->save();

            return $this->generateSuccessRedirect($form);

        } catch (\Exception $e) {
            $this->setupFormErrorContext(
                Translator::getInstance()->trans(
                    "Error",
                    [],
                    ChronopostPickupPoint::DOMAIN_NAME
                ),
                $e->getMessage(),
                $form
            );
            return $this->generateErrorRedirect($form);
        }
    }
}