<?php
namespace TheliaStore\Controller\Admin;

use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Tools\URL;
use TheliaStore\Form\StoreConfigForm;
use TheliaStore\Model\Map\StoreConfigTableMap;
use TheliaStore\Model\StoreConfig;
use TheliaStore\Model\StoreConfigQuery;

class ConfigController extends BaseAdminController
{

    public function getConfigAction()
    {
        return $this->render("module-configure", ['module_code' => 'TheliaStore']);
    }

    public function updateConfigAction()
    {
        $con = Propel::getWriteConnection(StoreConfigTableMap::DATABASE_NAME);
        $con->beginTransaction();

        try {
            $request = $this->getRequest();

            $myForm = new StoreConfigForm($request);

            $form = $this->validateForm($myForm);
            $myData = $form->getData();

            if (isset($myData['id']) && $myData['id'] > 0) {
                $myObject = StoreConfigQuery::create()->findPk($myData['id']);
                $myObject->setApiToken($myData['api_token'])
                    ->setApiKey($myData['api_key'])
                    ->setApiUrl($myData['api_url'])
                    ->save($con);
            } else {
                var_dump($myData);
                $myObject = new StoreConfig();
                $myObject->setApiToken($myData['api_token'])
                    ->setApiKey($myData['api_key'])
                    ->setApiUrl($myData['api_url'])
                    ->save($con);
            }
            $con->commit();
            return RedirectResponse::create(URL::getInstance()->absoluteUrl('/admin/module/TheliaStore'));
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }
}