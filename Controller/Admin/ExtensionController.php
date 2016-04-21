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
use Thelia\Core\Security\Token\TokenProvider;
use Thelia\Model\ConfigQuery;
use Thelia\Model\ModuleQuery;
use TheliaStore\Model\StoreExtensionQuery;
use TheliaStore\TheliaStore;

class ExtensionController extends BaseAdminController
{
    public function defaultAction($extension_id){
        return $this->render('extension-detail', array('extension_id'=>$extension_id,'category_id'=>0, 'sub_category_id'=>0));
    }

    /**
     * @param $extension_id : id de l'extension
     * @param $version : numéro de la version
     * @return bool
     */
    public static function extractVersion($extension_id,$num_version){
        $myExtension = StoreExtensionQuery::create()->findOneByExtensionId($extension_id);
        if($myExtension) {
            $fs = new Filesystem();
            if($fs->exists(THELIA_LOCAL_DIR . 'tmp_modules' . DS . $myExtension->getExtensionName() . DS . $num_version . DS . $num_version . '.zip')){
                $zip = new \ZipArchive();
                $zip->open(THELIA_LOCAL_DIR . 'tmp_modules' . DS . $myExtension->getExtensionName() . DS . $num_version . DS . $num_version . '.zip');
                //On dézip dans ./modules
                $zip->extractTo(THELIA_LOCAL_DIR . 'modules');
                return true;
            }
        }
        return false;
    }

    /**
     * @param $extension_id : id de l'extension
     * @param $version_id : id de la version
     * @param $num_version : numéro de la version
     * @return \Symfony\Component\HttpFoundation\Response|static
     */
    public static function downloadVersion($extension_id,$version_id,$num_version){
        $api = TheliaStore::getApi();

        $session = new Session();
        $dataAccount = $session->get('storecustomer');

        $param = array();
        $param['extension_id'] = $extension_id;
        $param['customer_id'] = $dataAccount['ID'];
        $param['thelia_version'] = ConfigQuery::getTheliaSimpleVersion();

        $myExtension = StoreExtensionQuery::create()->findOneByExtensionId($extension_id);
        $extension_name = $myExtension->getExtensionName();

        $fs = new Filesystem();
        if($fs->exists(THELIA_LOCAL_DIR . 'tmp_modules' . DS . $extension_name . DS . $num_version . DS . $num_version . '.zip')) {
            if(ExtensionController::extractVersion($extension_id,$num_version)){
                return JsonResponse::create(['msg' => 'Opération terminé'], 200);
            }
        }
        else{
            list($status, $data) = $api->doList('extensions/' . $extension_id . '/download/' . $version_id, $param);

            if ($status == 200) {

                if (!$fs->exists(THELIA_LOCAL_DIR . 'tmp_modules' . DS . $extension_name . DS . $num_version . DS)) {
                    $fs->mkdir(THELIA_LOCAL_DIR . 'tmp_modules' . DS . $extension_name . DS . $num_version . DS);
                }

                $fileSize = file_put_contents(THELIA_LOCAL_DIR . 'tmp_modules' . DS . $extension_name . DS . $num_version . DS . $num_version . '.zip', $data);

                if (!$fileSize) {
                    return JsonResponse::create(['msg' => 'Erreur'], 500);
                }

                //if($this->extractVersion($extension_id,$num_version)){
                if(ExtensionController::extractVersion($extension_id,$num_version)){
                    return JsonResponse::create(['msg' => 'Opération terminé'], 200);
                }
                /*
                $zip = new \ZipArchive();
                $zip->open(THELIA_LOCAL_DIR . 'tmp_modules' . DS . $extension_name . DS . $num_version . DS . $num_version . '.zip');
                //On dézip dans ./modules
                $zip->extractTo(THELIA_LOCAL_DIR . 'modules');
                //$zip->extractTo(THELIA_LOCAL_DIR . 'tmp_modules' . DS . $titleExtension . DS . $num_version);
                */

            }
        }
        return JsonResponse::create(['msg' => 'Erreur'], 500);

    }

    public function updateAction($extension_id,$version_id){
        if (TheliaStore::isConnected() === 1) {
            $num_version = $this->getRequest()->get('num_version');

            //On essay d'extraire la version depuis le dossier local
            //if(!$this->extractVersion($extension_id,$num_version)){
            if(ExtensionController::extractVersion($extension_id,$num_version)){
                //return $this->downloadVersion($extension_id,$version_id,$num_version);
                return ExtensionController::downloadVersion($extension_id,$version_id,$num_version);
            }
            else{
                return JsonResponse::create(['msg' => 'Opération terminé'], 200);
            }

        }
        return JsonResponse::create(['msg' => 'Erreur'], 500);
    }

    public function downloadAction($extension_id){

        if (TheliaStore::isConnected() === 1) {

            $api = TheliaStore::getApi();

            $session = new Session();
            $dataAccount = $session->get('storecustomer');

            $param = array();
            $param['extension_id'] = $extension_id;
            $param['customer_id'] = $dataAccount['ID'];
            $param['thelia_version'] = ConfigQuery::getTheliaSimpleVersion();

            //Récupération de la dernière version de l'extension compatible avec le thelia du client
            list($status, $data) = $api->doList('extensions/' . $extension_id . '/lastversion', $param);

            /*
             $data : [
                    'extensionversion' => $product_id,
                    'title' => $titleExtension,
                    'num' => $num_version
              ]
             */

            if ($status == 200) {
                $version_id = $data[0]['extensionversion'];
                $num_version = $data[0]['num'];
                //return $this->downloadVersion($extension_id,$version_id,$num_version);
                return ExtensionController::downloadVersion($extension_id,$version_id,$num_version);
            }

            return JsonResponse::create(['msg' => 'Erreur'], 200);

        }

    }



    public function cartAction(){
        return $this->render('store-cart', array('category_id'=>0, 'sub_category_id'=>0));
    }

    public function cartAddAction($extension_id){

        if(TheliaStore::isConnected() === 1){

            $api = TheliaStore::getApi();

            $param = array();
            $param['product'] = $extension_id;
            $param['customer_id'] = $this->getRequest()->get('customer');

            list($status, $data) = $api->doPost('extensions/'.$extension_id.'/addcart',$param);

            $this->setCurrentRouter('router.theliastore');

            return $this->generateRedirectFromRoute(
                'theliastore.extension.cart',
                array(),
                array()
            );

        }
        else{
            $this->setCurrentRouter('router.theliastore');

            return $this->generateRedirectFromRoute(
                'theliastore.login.store.account',
                array(),
                array()
            );
        }

    }
}