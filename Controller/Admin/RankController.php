<?php
namespace TheliaStore\Controller\Admin;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Polyfill\Util\Binary;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Translation\Translator;
use TheliaStore\TheliaStore;

class RankController extends BaseAdminController
{
    public function rankAction($object_type,$object_id){
        if (TheliaStore::isConnected() === 1) {
            $api = TheliaStore::getApi();

            $session = new Session();
            $dataAccount = $session->get('storecustomer');

            $param = array();
            $param['object_type'] = $object_type;
            $param['object_id'] = $object_id;
            $param['customer_id'] = $dataAccount['ID'];
            $param['value'] = $this->getRequest()->get('value');

            //var_dump($param);
            list($status, $data) = $api->doPost('ranks', $param);
            //var_dump($status);
            //var_dump($data);

            if($status == 201){
                return JsonResponse::create(
                    [
                    'error' => 'OperationComplete',
                    'message' => Translator::getInstance()->trans('OperationComplete',[],TheliaStore::DOMAIN_NAME),
                    ],
                    200
                );
            }
            else{
                TheliaStore::extractError($error, $message, $data);
                return JsonResponse::create(array('error'=>$error,'message'=>$message), 500);
            }

        }
        return JsonResponse::create(
            [
            'error' => 'CustomerNotLogged',
            'message' => Translator::getInstance()->trans('CustomerNotLogged',[],TheliaStore::DOMAIN_NAME),
            ],
            500
        );
    }


}