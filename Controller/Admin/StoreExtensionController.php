<?php
namespace TheliaStore\Controller\Admin;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Polyfill\Util\Binary;
use Thelia\Api\Client\Client;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Security\Token\TokenProvider;
use Thelia\Exception\InvalidModuleException;
use Thelia\Model\ConfigQuery;
use Thelia\Module\ModuleManagement;
use TheliaStore\Model\StoreExtension;
use TheliaStore\Model\StoreExtensionQuery;
use TheliaStore\TheliaStore;

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

    public function downloadAction(){
        $tabextension = unserialize($this->getRequest()->get('tabextension'));
        return $this->render('store-extensions-download', array('tabextension'=>$tabextension));
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

    public function checkExtensionAction(){
        if(TheliaStore::isConnected() === 1) {

            $api = TheliaStore::getApi();
            $session = new Session();
            $dataAccount = $session->get('storecustomer');

            $param = array();
            $param['customer_id'] = $dataAccount['ID'];
            $param['thelia_version'] = ConfigQuery::getTheliaSimpleVersion();
            $param['customer_domain'] = ConfigQuery::read('url_site');

            list($status, $data) = $api->doList('customer-extensions/getall',$param);
            /*
            [
                'extension_id'
                'version_id'
                'product_id'
                'token'
                'code'
                'product_title'
                'num'
            ]
            */
            if($status == 200){
                var_dump($data);
                /*
                 * Ajout dans store_extension
                 */

                foreach($data as $dataExtension){

                    $myExtension = StoreExtensionQuery::create()->findOneByExtensionId($dataExtension['extension_id']);
                    if(!$myExtension){
                        $myExtension = new StoreExtension();
                        $myExtension->setExtensionId($dataExtension['extension_id'])
                            ->setProductExtensionId($dataExtension['product_id'])
                            ->setToken($dataExtension['token'])
                            ->setCode($dataExtension['code'])
                            ->setExtensionName($dataExtension['product_title'])
                            ->setInstallationState(1)
                            ->save();
                    }
                }

                $this->setCurrentRouter('router.theliastore');
                return $this->generateRedirectFromRoute(
                    'theliastore.myextension.download',
                    array(),
                    array('tabextension'=>serialize($data))
                );


            }
            else{

                TheliaStore::extractError($data);

                $this->setCurrentRouter('router.theliastore');

                return $this->generateRedirectFromRoute(
                    'theliastore.myextension',
                    array(),
                    array()
                );

            }
        }
        else{
            $this->setCurrentRouter('router.theliastore');

            return $this->generateRedirectFromRoute(
                'theliastore.myextension',
                array(),
                array()
            );
        }
    }

    public function deleteExtensionAction(){
        if(TheliaStore::isConnected() === 1) {

            $request = $this->getRequest();

            $myStoreExtension = StoreExtensionQuery::create()->findPk($request->get('id',0));

            if($myStoreExtension){

                $api = TheliaStore::getApi();
                $session = new Session();
                $dataAccount = $session->get('storecustomer');

                $param = array();
                $param['customer_id'] = $dataAccount['ID'];
                $param['customer_domain'] = ConfigQuery::read('url_site');
                $param['token'] = $myStoreExtension->getToken();
                $param['extension'] = $myStoreExtension->getExtensionId();

                list($status, $data) = $api->doDelete('customer-extensions/delete',$param['customer_id'], $param);

                //var_dump($status);
                //var_dump($data);

                if($status == 204){
                    //Delete local files
                    $fs = new Filesystem();
                    if($fs->exists(THELIA_LOCAL_DIR . 'tmp_modules' . DS . $myStoreExtension->getExtensionName())){
                        $fs->remove(THELIA_LOCAL_DIR . 'tmp_modules' . DS . $myStoreExtension->getExtensionName());
                    }
                    //Delete in DB
                    $myStoreExtension->delete();

                    $this->setCurrentRouter('router.theliastore');

                    return $this->generateRedirectFromRoute(
                        'theliastore.myextension',
                        array(),
                        array()
                    );

                }

            }
        }

        $this->setCurrentRouter('router.theliastore');

        return $this->generateRedirectFromRoute(
            'theliastore.myextension',
            array(),
            array()
        );

    }

}