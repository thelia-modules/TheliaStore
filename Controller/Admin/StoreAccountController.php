<?php
/**
 * Created by PhpStorm.
 * User: E-FUSION-JULIEN
 * Date: 01/04/2016
 * Time: 11:04
 */

namespace TheliaStore\Controller\Admin;

use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Form\CustomerLogin;
use TheliaStore\Form\StoreAccountCreationForm;
use TheliaStore\Form\StoreAccountUpdateForm;
use TheliaStore\Form\StoreAccountUpdatePasswordForm;
use TheliaStore\TheliaStore;

class StoreAccountController extends BaseAdminController
{

    public function defaultAction(){
        return $this->render('store-account');
    }

    public function updateFormAction(){
        return $this->render('account-updateform');
    }
    public function updateAction(){

        $request = $this->getRequest();
        $myForm = new StoreAccountUpdateForm($request);

        $form = $this->validateForm($myForm);
        $myData = $form->getData();

        $client = TheliaStore::getApi();

        $dataApi['id'] = $myData['id'];
        $dataApi['email'] = $myData['email'];
        $dataApi['firstname'] = $myData['firstname'];
        $dataApi['lastname'] = $myData['lastname'];
        $dataApi['address1'] = $myData['address1'];
        $dataApi['zipcode'] = $myData['zipcode'];
        $dataApi['city'] = $myData['city'];
        $dataApi['country'] = $myData['country'];
        $dataApi['title'] = $myData['title'];
        $dataApi['lang'] = $myData['lang'];

        //var_dump($dataApi);

        list($status, $data) = $client->doPut('customers',$dataApi);
        //var_dump($status);
        //var_dump($data);

        if($status == 201 && is_array($data)){
            if(isset($data[0]['ID']) && $data[0]['ID']>0){
                $session = new Session();
                $session->set('storecustomer',$data[0]);
            }
        }

        return $this->render('account-updateform');
    }

    public function changePasswordFormAction(){
        return $this->render('account-changepasswordform');
    }
    public function changePasswordAction(){

        $request = $this->getRequest();
        $myForm = new StoreAccountUpdatePasswordForm($request);

        $form = $this->validateForm($myForm);
        $myData = $form->getData();

        $client = TheliaStore::getApi();

        $dataApi['id'] = $myData['id'];
        $dataApi['email'] = $myData['email'];
        $dataApi['password'] = $myData['password'];

        var_dump($dataApi);

        list($status, $data) = $client->doPut('customers',$dataApi);
        var_dump($status);
        var_dump($data);

        if($status == 201 && is_array($data)){
            if(isset($data[0]['ID']) && $data[0]['ID']>0){
                $session = new Session();
                $session->set('storecustomer',$data[0]);
            }
        }

        return $this->render('account-changepasswordform');
    }

    public function createFormAction(){
        return $this->render('account-createform');
    }

    public function createAction(){
        $request = $this->getRequest();
        $myForm = new StoreAccountCreationForm($request);

        $form = $this->validateForm($myForm);
        $myData = $form->getData();

        $client = TheliaStore::getApi();

        $dataApi['email'] = $myData['email'];
        $dataApi['password'] = $myData['password'];
        $dataApi['firstname'] = $myData['firstname'];
        $dataApi['lastname'] = $myData['lastname'];
        $dataApi['address1'] = $myData['address1'];
        $dataApi['zipcode'] = $myData['zipcode'];
        $dataApi['city'] = $myData['city'];
        $dataApi['country'] = $myData['country'];
        $dataApi['title'] = $myData['title'];
        $dataApi['lang_id'] = $myData['lang'];

        //var_dump($dataApi);

        list($status, $data) = $client->doPost('customers',$dataApi);

        //var_dump($status);
        //var_dump($data);

        if($status == 201){
            if(isset($data[0]['ID']) && $data[0]['ID']>0){
                $session = new Session();
                $session->set('isconnected','1');
                $session->set('storecustomer',$data[0]);

                $this->setCurrentRouter('router.TheliaStore');
                return $this->generateRedirectFromRoute(
                    'theliastore.store',
                    array(),
                    array()

                );
            }
        }
        $error = 'Désolé, une erreur est survenu';
        if(isset($data['error']))
            $error = $data['error'];

        return $this->render('account-createform', array('error'=>$error));
    }

    public function loginAction(){

        $request = $this->getRequest();
        $myForm = new CustomerLogin($request);

        $form = $this->validateForm($myForm);
        $myData = $form->getData();

        $client = TheliaStore::getApi();
        list($status, $data) = $client->doPost("customers/checkLogin", array("email"=>$myData['email'],"password"=>$myData['password']));

        //var_dump($status);
        //var_dump($data);
        if($status == '200' && is_array($data)){
            if(isset($data[0]['ID']) && $data[0]['ID']>0){
                $session = new Session();
                $session->set('isconnected','1');
                $session->set('storecustomer',$data[0]);

                $this->setCurrentRouter('router.TheliaStore');
                return $this->generateRedirectFromRoute(
                    'theliastore.store',
                    array(),
                    array()

                );

            }
        }

        return $this->render('account-loginform');
    }

    public function loginFormAction(){
        return $this->render('account-loginform');
    }

    public function logoutAction(){
        $session = new Session();
        $session->remove('isconnected');
        $session->remove('storecustomer');

        $this->setCurrentRouter('router.TheliaStore');
        return $this->generateRedirectFromRoute(
            'theliastore.store',
            array(),
            array()

        );
    }

}