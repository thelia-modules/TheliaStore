<?php
namespace TheliaStore\Controller\Admin;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Polyfill\Util\Binary;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Translation\Translator;
use TheliaStore\TheliaStore;

class CommentController extends BaseAdminController
{

    public function getComments($ref, $ref_id)
    {
        return $this->render('includes/comments', array('ref' => $ref, 'ref_id' => $ref_id, 'status' => '1'));
    }

    public function commentAction($ref, $ref_id)
    {
        if (TheliaStore::isConnected() === 1) {
            $api = TheliaStore::getApi();

            $session = new Session();
            $dataAccount = $session->get('storecustomer');

            $param = array();
            $param['ref'] = $ref;
            $param['ref_id'] = $ref_id;
            $param['customer_id'] = $dataAccount['ID'];
            $param['comment'] = $this->getRequest()->get('comment');
            $param['title'] = $this->getRequest()->get('title');

            //var_dump($param);
            list($status, $data) = $api->doPost('comment', $param);
            //var_dump($status);
            //var_dump($data);

            if ($status == 201) {
                return JsonResponse::create(
                    [
                        'error' => 'OperationComplete',
                        'message' => Translator::getInstance()->trans('OperationComplete', [],
                            TheliaStore::DOMAIN_NAME),
                    ],
                    200
                );
            } else {
                TheliaStore::extractError($data, $error, $message);
                return JsonResponse::create(array('error' => $error, 'message' => $message), 500);
            }

        }
        return JsonResponse::create(
            [
                'error' => 'CustomerNotLogged',
                'message' => Translator::getInstance()->trans('CustomerNotLogged', [], TheliaStore::DOMAIN_NAME),
            ],
            500
        );
    }


}