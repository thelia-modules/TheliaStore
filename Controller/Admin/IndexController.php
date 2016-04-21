<?php
namespace TheliaStore\Controller\Admin;

use Symfony\Component\HttpFoundation\Cookie;
use Thelia\Api\Client\Client;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\Security\Token\TokenProvider;

class IndexController extends BaseAdminController
{
    public function testAction(){
        return $this->render('test');
    }
    public function defaultAction(){
        //https://github.com/thelia/thelia-api-client/blob/master/tests/ClientTest.php

        //$secureKey = pack('H*', file_get_contents("api_key_file.key"));

        //$signature = hash_hmac('sha1', '', $secureKey);

        $client = new Client(
            "3517AEB52E441B306276412F1",
            "E7E221D3A77A37C819A288F1C8CECE0BAE41FADC7F7144C6",
            "http://127.0.0.1/thelia/web"
        );
        list($status, $data) = $client->doPost("customers/checkLogin", array("email"=>'julien@e-fusion.fr',"password"=>'julien'));
        //var_dump($status);
        //var_dump($data);
        /*
        list($status, $data) = $client->doPost("customers/checkLogin", array("email"=>'julien@e-fusion.fr',"password"=>'julien'));
        var_dump($status);
        var_dump($data);
        */

        $dataUser = array("email"=>'julien@e-fusion.fr',"password"=>'julien');

        $serializeDataUser = serialize($dataUser);
        $encodeData = base64_encode($serializeDataUser);

        /*
         * protected function createRememberMeCookie(UserInterface $user, $cookieName, $cookieExpiration)
    {
        $ctp = new CookieTokenProvider();

        $ctp->createCookie(
            $user,
            $cookieName,
            $cookieExpiration
        );
    }
         */
        var_dump($encodeData);

        $testC = $this->getRequest()->cookies->get('theliastoreconnect');
        var_dump($testC);

        $origineData = base64_decode($testC);
        var_dump($origineData);

        $cookie_info = array(
            'name' => 'theliastoreconnect',
            'value' => $encodeData,
            'time' => time() + 3600 * 24 * 7
        );
        $cookie = new Cookie($cookie_info['name'], $cookie_info['value'], $cookie_info['time']);

        $response = new Response();
        $response->headers->setCookie($cookie);
        $response->send();


        return $this->render('theliastore-index');
    }
}