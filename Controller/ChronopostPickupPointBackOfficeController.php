<?php

declare(strict_types=1);

namespace ChronopostPickupPoint\Controller;


use ChronopostPickupPoint\ChronopostPickupPoint;
use ChronopostPickupPoint\Config\ChronopostPickupPointConst;
use ChronopostPickupPoint\Form\ChronopostPickupPointConfigurationForm;
use ChronopostPickupPoint\Form\ChronopostPickupPointDeliveryModeForm;
use ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryModeQuery;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Translation\Translator;
use Symfony\Component\Routing\Attribute\Route;
use Thelia\Model\LangQuery;

/**
 */
#[Route('/admin/module/ChronopostPickupPoint', name: 'ChronopostPickupPoint')]
class ChronopostPickupPointBackOfficeController extends BaseAdminController
{
    /**
     * Render the module config page
     *
     * @return Response
     */
    public function viewAction(string $tab = 'configure')
    {
        return $this->render(
            'module-configure',
            [
                'module_code' => 'ChronopostPickupPoint',
                'current_tab' => $tab,
            ]
        );
    }

    /**
     */
    #[Route('/saveLabel', name: '_saveLabel')]
    public function saveLabel(RequestStack $requestStack)
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], 'ChronopostPickupPoint', AccessManager::UPDATE)) {
            return $response;
        }

        $request = $requestStack->getCurrentRequest();
        if (null === $request){
            throw new \Exception('Request not found');
        }

        $labelNbr = (string) $request->query->get('labelNbr');
        $labelDir = (string) $request->query->get('labelDir');

        // Confine the resolved file under THELIA_LOCAL_DIR (where labels are stored) and strip any
        // directory part from the file name, defeating path traversal / arbitrary file read.
        $file = realpath($labelDir.DIRECTORY_SEPARATOR.basename($labelNbr));
        $baseDir = realpath(THELIA_LOCAL_DIR);

        if (false === $file || false === $baseDir || !str_starts_with($file, $baseDir.DIRECTORY_SEPARATOR)) {
            return new Response(Translator::getInstance()->trans('Label not found'), Response::HTTP_NOT_FOUND);
        }

        return new BinaryFileResponse(
            $file,
            200,
            ['Content-Type' => 'application/octet-stream'],
            false,
            'attachment'
        );
    }

    /**
     * Save configuration form - Chronopost informations
     *
     * @return mixed|null|\Symfony\Component\HttpFoundation\Response
     */
    #[Route('/config', name: '_config', methods: ['POST'])]
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
     */
    #[Route('/delivery-mode', name: '_delivery_mode', methods: ['POST'])]
    public function updateDeliveryModeTitle(Request $request)
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], 'ChronopostHomeDelivery', AccessManager::UPDATE)) {
            return $response;
        }

        $form = $this->createForm(ChronopostPickupPointDeliveryModeForm::getName());

        try {
            $data = $this->validateForm($form)->getData();

            $deliveryMode = ChronopostPickupPointDeliveryModeQuery::create()->findPk($data['delivery_mode_id']);
            $lang = $request->hasSession() ? $request->getSession()->get('thelia.admin.edition.lang') : null;
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
