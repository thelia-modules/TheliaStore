<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/
namespace TheliaStore\Controller\Admin;

use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Translation\Translator;
use TheliaStore\TheliaStore;

class CommentController extends BaseAdminController
{
    /**
     * @param $ref ie : product, category, content ...
     * @param $ref_id
     * @return \Thelia\Core\HttpFoundation\Response
     */
    public function getComments($ref, $ref_id)
    {
        return $this->render('includes/comments', array('ref' => $ref, 'ref_id' => $ref_id, 'status' => '1'));
    }

    /**
     * @param $ref ie : product, category, content ...
     * @param $ref_id
     * @return \Symfony\Component\HttpFoundation\Response|static
     */
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

            list($status, $data) = $api->doPost('comment', $param);

            if ($status == 201) {
                return JsonResponse::create(
                    [
                        'error' => 'OperationComplete',
                        'message' => Translator::getInstance()->trans(
                            'OperationComplete',
                            [],
                            TheliaStore::DOMAIN_NAME
                        ),
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
