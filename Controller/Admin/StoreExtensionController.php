<?php
namespace TheliaStore\Controller\Admin;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Polyfill\Util\Binary;
use Thelia\Api\Client\Client;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Security\Token\TokenProvider;
use Thelia\Exception\InvalidModuleException;
use Thelia\Model\ConfigQuery;
use Thelia\Module\ModuleManagement;
use TheliaStore\TheliaStore;
use Symfony\Component\Config\Definition\Exception\Exception;
use Thelia\Log\Tlog;

class StoreExtensionController extends BaseAdminController
{
    public function defaultAction(){
       /* if (null !== $response = $this->checkAuth(AdminResources::MODULE, array(), AccessManager::VIEW)) {
            return $response;
        }

        try {
            $moduleManagement = new ModuleManagement();
            $moduleManagement->updateModules($this->getContainer());
        } catch (InvalidModuleException $ex) {
            $this->moduleErrors = $ex->getErrors();
        } catch (Exception $ex) {
            Tlog::getInstance()->addError("Failed to get modules list:", $ex);
        }
        */

        $moduleManagement = new ModuleManagement();
        $moduleManagement->updateModules($this->getContainer());
        return $this->render('store-extensions');
    }

    public function getVersionsAction($extension_id){
        $version = $this->getRequest()->get('version');
        return $this->render('modal-store-extensions-versions',array('extension_id' => $extension_id, 'version' => $version));
    }

    /**
     * @param $extension_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeVersionAction($extension_id){
        $version_id = $this->getRequest()->get('version');
        $num_version = $this->getRequest()->get('num_version_'.$version_id);

        ExtensionController::downloadVersion($extension_id,$version_id,$num_version);

        $this->setCurrentRouter('router.theliastore');

        return $this->generateRedirectFromRoute(
            'theliastore.myextension',
            array(),
            array()
        );

    }

}