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

use Thelia\Api\Client\Client;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Module\BaseModule;
use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;

class TheliaStore extends BaseModule
{

    /** @var string */
    const DOMAIN_NAME = 'theliastore';
    /** @var string */
    const BO_DOMAIN_NAME = 'theliastore.bo.default';
    /** @var string */
    //const API_URL = 'http://thelia-marketplace.openstudio-lab.com';
    const API_URL = 'http://127.0.0.1/thelia-marketplace/web';

    /**
     * @return \Thelia\Api\Client\Client
     */
    static function getApi()
    {

        //local config

        $client = new Client(
            "502F9AF505B57FE50C7AA9922",
            "6C94E81DA7FF0FD12CC0179FD36B2DB706E23835A1DEE0BF",
            "http://127.0.0.1/thelia-marketplace/web"
        );

        /*
        //Online config
        $client = new Client(
            "100FBFED0B742F288013F1ED1",
            "64285C2A60E9F941A7B8EB868A918032C07CDD0C1DD184FB",
            "http://thelia-marketplace.openstudio-lab.com"
        );
        */

        return $client;
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
     */
    static function extractError($data = array(),&$error='',&$message='')
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
        /*
        $database = new Database($con);
        $database->insertSql(null, array(__DIR__ . '/Config/thelia.sql'));
        */
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
            )/*,
            array(
                "type" => TemplateDefinition::BACK_OFFICE,
                "code" => "store.create_account",
                "title" => "Get the store account creation form",
                "description" => "Get the store account creation form",
                "active" => true,
            )*/
        );
    }
}
