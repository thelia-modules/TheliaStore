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
namespace TheliaStore;

use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Module\BaseModule;
use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;
use TheliaClientApi\Classes\Client;

class TheliaStore extends BaseModule
{

    /** @var string */
    const DOMAIN_NAME = 'theliastore';
    /** @var string */
    const BO_DOMAIN_NAME = 'theliastore.bo.default';

    /**
     * @return null|Client
     */
    static function getApi()
    {
        $client = new Client();
        if ($client->init()) {
            return $client;
        }
        return null;
    }

    /**
     * Function for test the customer auth
     * @return int
     */
    static function isConnected()
    {
        /*
         * TODO : cookie for auto connection
         */
        $session = new Session();
        $connected = $session->get('isconnected', null);
        if ($connected) {
            return 1;
        }
        return 0;
    }

    /**
     * @param array $data
     * @param string $error
     * @param string $message
     */
    static function extractError($data = array(), &$error = '', &$message = '')
    {
        if (isset($data['error'])) {
            $error = $data['error'];
        }
        if (isset($data['message'])) {
            $message = $data['message'];
            $session = new Session();
            $session->getFlashBag()->add('store_error', $message);
        }
    }

    public function postActivation(ConnectionInterface $con = null)
    {
        $database = new Database($con);
        $database->insertSql(null, array(__DIR__ . '/Config/thelia.sql'));
    }

    public function getHooks()
    {
        //Add a new hook for display ranking
        return array(
            array(
                "type" => TemplateDefinition::BACK_OFFICE,
                "code" => "extension.get_form_payment",
                "title" => "Get api form payement",
                "description" => "Get api form payement",
                "active" => true,
            ),
            array(
                "type" => TemplateDefinition::BACK_OFFICE,
                "code" => "extension.chose_cart_type",
                "title" => "Get api form payement",
                "description" => "Get api form payement",
                "active" => true,
            ),
            array(
                "type" => TemplateDefinition::BACK_OFFICE,
                "code" => "store.error",
                "title" => "Display error in store",
                "description" => "Display error in store",
                "active" => true,
            ),
            array(
                "type" => TemplateDefinition::BACK_OFFICE,
                "code" => "store.create_account",
                "title" => "Get the store account creation form",
                "description" => "Get the store account creation form",
                "active" => true,
            )
        );
    }
}
