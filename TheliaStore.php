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
use Thelia\Module\BaseModule;

class TheliaStore extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'theliastore';
    /** @var string */
    const API_URL = 'http://127.0.0.1/thelia/web';

    /**
     * @return \Thelia\Api\Client\Client
     */
    static function getApi(){
        $client = new Client(
            "3517AEB52E441B306276412F1",
            "E7E221D3A77A37C819A288F1C8CECE0BAE41FADC7F7144C6",
            "http://127.0.0.1/thelia/web"
        );
        return $client;
    }

    /**
     * Function for test the customer auth
     * @return int
     */
    static function isConnected(){
        /*
         * TODO : cookie for auto connection
         */
        $session = new Session();
        $connected = $session->get('isconnected',null);
        if($connected){
            return 1;
        }
        return 0;
    }
}
