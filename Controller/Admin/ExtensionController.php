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
use Thelia\Core\Translation\Translator;
use Thelia\Model\ConfigQuery;
use Thelia\Model\ModuleQuery;
use TheliaStore\Model\StoreExtensionQuery;
use TheliaStore\TheliaStore;

class ExtensionController extends BaseAdminController
{
    public function defaultAction($extension_id)
    {
        return $this->render('extension-detail',
            array('extension_id' => $extension_id, 'category_id' => 0, 'sub_category_id' => 0));
    }

    public function searchAction()
    {
        $search_term = $this->getRequest()->get('search_term');
        return $this->render('store-search', array('search_term' => $search_term));
    }

    /**
     * @param $extension_id : id de l'extension
     * @param $version : numéro de la version
     * @return bool
     */
    public static function extractVersion($extension_id, $num_version)
    {
        $myExtension = StoreExtensionQuery::create()->findOneByExtensionId($extension_id);
        if ($myExtension) {
            $fs = new Filesystem();
            if ($fs->exists(THELIA_LOCAL_DIR . 'tmp_modules' . DS . $myExtension->getExtensionName() . DS . $num_version . DS . $num_version . '.zip')) {
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
    public static function downloadVersion($extension_id, $version_id, $num_version, $extension_name = '')
    {
        $api = TheliaStore::getApi();

        $session = new Session();
        $dataAccount = $session->get('storecustomer');

        $param = array();
        $param['extension_id'] = $extension_id;
        $param['customer_id'] = $dataAccount['ID'];
        $param['thelia_version'] = ConfigQuery::getTheliaSimpleVersion();

        if ($extension_name === '') {
            $myExtension = StoreExtensionQuery::create()->findOneByExtensionId($extension_id);
            $extension_name = $myExtension->getExtensionName();
        }

        $fs = new Filesystem();
        if ($fs->exists(THELIA_LOCAL_DIR . 'tmp_modules' . DS . $extension_name . DS . $num_version . DS . $num_version . '.zip')) {
            if (ExtensionController::extractVersion($extension_id, $num_version)) {
                return JsonResponse::create([
                    'msg' => Translator::getInstance()->trans('Finished', [], TheliaStore::BO_DOMAIN_NAME)
                ], 200);
            } else {
                return JsonResponse::create([
                    'msg' => Translator::getInstance()->trans('Error on archive', [], TheliaStore::BO_DOMAIN_NAME)
                ], 500);
            }
        } else {
            list($status, $data) = $api->doList('extensions/' . $extension_id . '/download/' . $version_id, $param);

            if ($status == 200) {

                if (!$fs->exists(THELIA_LOCAL_DIR . 'tmp_modules' . DS . $extension_name . DS . $num_version . DS)) {
                    $fs->mkdir(THELIA_LOCAL_DIR . 'tmp_modules' . DS . $extension_name . DS . $num_version . DS);
                }

                $fileSize = file_put_contents(THELIA_LOCAL_DIR . 'tmp_modules' . DS . $extension_name . DS . $num_version . DS . $num_version . '.zip',
                    $data);

                if (!$fileSize) {
                    return JsonResponse::create([
                        'msg' => Translator::getInstance()->trans('Error on archive creation', [],
                            TheliaStore::BO_DOMAIN_NAME)
                    ], 500);
                }

                if (ExtensionController::extractVersion($extension_id, $num_version)) {
                    return JsonResponse::create([
                        'msg' => Translator::getInstance()->trans('Finished', [], TheliaStore::BO_DOMAIN_NAME)
                    ], 200);
                }

            } else {
                return JsonResponse::create([
                    'msg' => Translator::getInstance()->trans('Error downloading', [], TheliaStore::BO_DOMAIN_NAME)
                ], 500);
            }
        }
        return JsonResponse::create([
            'msg' => Translator::getInstance()->trans('Error downloading', [], TheliaStore::BO_DOMAIN_NAME)
        ], 500);

    }

    /**
     * Download a version of an extension, based on the version_id
     * @param $extension_id
     * @param $version_id
     * @return \Symfony\Component\HttpFoundation\Response|ExtensionController|static
     */
    public function updateAction($extension_id, $version_id)
    {
        if (TheliaStore::isConnected() === 1) {
            $num_version = $this->getRequest()->get('num_version');

            //On essay d'extraire la version depuis le dossier local
            if (!ExtensionController::extractVersion($extension_id, $num_version)) {
                return ExtensionController::downloadVersion($extension_id, $version_id, $num_version);
            } else {
                return JsonResponse::create([
                    'msg' => Translator::getInstance()->trans('Finished', [], TheliaStore::BO_DOMAIN_NAME)
                ], 200);
            }

        }
        return JsonResponse::create([
            'msg' => Translator::getInstance()->trans('Error', [], TheliaStore::BO_DOMAIN_NAME)
        ], 500);
    }

    /**
     * Download a version of an extension, based on the version_id
     * @param $extension_id
     * @param $version_id
     * @return \Symfony\Component\HttpFoundation\Response|ExtensionController|static
     */
    public function downloadVersionAction($extension_id, $version_id)
    {
        if (TheliaStore::isConnected() === 1) {
            $num_version = $this->getRequest()->get('num_version');

            //On essay d'extraire la version depuis le dossier local
            if (!ExtensionController::extractVersion($extension_id, $num_version)) {
                return ExtensionController::downloadVersion($extension_id, $version_id, $num_version);
            } else {
                return JsonResponse::create([
                    'msg' => Translator::getInstance()->trans('Finished', [], TheliaStore::BO_DOMAIN_NAME)
                ], 200);
            }

        }
        return JsonResponse::create([
            'msg' => Translator::getInstance()->trans('Error', [], TheliaStore::BO_DOMAIN_NAME)
        ], 500);
    }

    /**
     * Download the last version of an extension, based on the product_id
     * @param $product_id
     * @return \Symfony\Component\HttpFoundation\Response|ExtensionController|static
     */
    public function downloadProductAction($product_id)
    {
        if (TheliaStore::isConnected() === 1) {
            $api = TheliaStore::getApi();

            $session = new Session();
            $dataAccount = $session->get('storecustomer');

            $param = array();
            $param['customer_id'] = $dataAccount['ID'];
            $param['thelia_version'] = ConfigQuery::getTheliaSimpleVersion();

            //Récupération de la dernière version de l'extension compatible avec le thelia du client
            list($status, $data) = $api->doList('product-extensions/' . $product_id . '/lastversion', $param);

            /*
             $data : [
                    'extension'=> extension_id,
                    'extensionversion'=> extension_version_id,
                    'title' => extension_title,
                    'num'=> extension_version_num
              ]
             */

            if ($status == 200) {
                $extension_id = $data[0]['extension'];
                $version_id = $data[0]['extensionversion'];
                $num_version = $data[0]['num'];
                $code = $data[0]['title'];
                return ExtensionController::downloadVersion($extension_id, $version_id, $num_version, $code);
            }

            return JsonResponse::create([
                'msg' => Translator::getInstance()->trans('Error', [], TheliaStore::BO_DOMAIN_NAME)
            ], 200);

        }
    }

}