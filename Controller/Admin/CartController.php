<?php
namespace TheliaStore\Controller\Admin;

use Symfony\Component\HttpFoundation\Cookie;
use Thelia\Api\Client\Client;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Security\Token\TokenProvider;
use Thelia\Model\ConfigQuery;
use TheliaStore\Model\StoreExtensionQuery;
use TheliaStore\Model\StoreExtension;
use TheliaStore\TheliaStore;

class CartController extends BaseAdminController
{

    public function cartAction(){
        return $this->render('store-cart', array('category_id'=>0, 'sub_category_id'=>0));
    }

    public function validateAction(){

        $cartId = $this->getRequest()->get('cart_id');

        if(TheliaStore::isConnected() === 1 && $cartId>0){

            $api = TheliaStore::getApi();

            $session = new Session();
            $dataAccount = $session->get('storecustomer');

            $param = array();
            $param['cart_id'] = $cartId;
            $param['customer_id'] = $dataAccount['ID'];
            $param['customer_domain'] = ConfigQuery::read('url_site');

            list($status, $data) = $api->doPost('cart/'.$cartId.'/validate',$param);

            /*
            [
                'order' =>  $order_id,
                'products' => [
                    'product_id' => $product_id,
                    'product_title' => $product_title,
                    'extension_id' => $extension_id,
                    'token' => $token,
                    'code' => $extension->getCode()
                ]
            ]
            */

            if($status == 200){

                /*
                 * Ajout dans store_extension
                 */
                $tabProducts = $data['products'];
                foreach($tabProducts as $product){

                    $myExtension = StoreExtensionQuery::create()->findOneByExtensionId($product['extension_id']);
                    if(!$myExtension){
                        $myExtension = new StoreExtension();
                        $myExtension->setExtensionId($product['extension_id'])
                            ->setProductExtensionId($product['product_id'])
                            ->setToken($product['token'])
                            ->setCode($product['code'])
                            ->setExtensionName($product['product_title'])
                            ->setInstallationState(1)
                            ->save();
                    }
                }

                $this->setCurrentRouter('router.theliastore');
                return $this->generateRedirectFromRoute(
                    'theliastore.cart.download',
                    array(),
                    array('downloadproduct'=>serialize($data['products']))
                );

            }
            else{

                $this->setCurrentRouter('router.theliastore');

                return $this->generateRedirectFromRoute(
                    'theliastore.extension.cart',
                    array(),
                    array()
                );

            }

        }
        else{
            $this->setCurrentRouter('router.theliastore');

            return $this->generateRedirectFromRoute(
                'theliastore.login.store.account',
                array(),
                array()
            );
        }

    }

    public function cartDownloadAction(){
        $downloadproduct = unserialize($this->getRequest()->get('downloadproduct'));
        return $this->render('store-cart-validate', array('category_id'=>0, 'sub_category_id'=>0, 'downloadproduct'=>$downloadproduct));
    }
    public function cartAddAction($product_id){

        if(TheliaStore::isConnected() === 1){

            $api = TheliaStore::getApi();

            $param = array();
            $param['product'] = $product_id;
            $param['customer_id'] = $this->getRequest()->get('customer');
            $param['customer_domain'] = ConfigQuery::read('url_site');

            list($status, $data) = $api->doPost('product-extensions/'.$product_id.'/addcart',$param);

            $message_error = '';
            $code_error = '';
            if($status != 200){
                if(isset($data['error']))
                    $code_error = $data['error'];
                if(isset($data['message']))
                    $message_error = $data['message'];
            }
            /*
            var_dump($status);
            var_dump($data);
            echo $data;
            */

            $this->setCurrentRouter('router.theliastore');

            return $this->generateRedirectFromRoute(
                'theliastore.extension.cart',
                array('message_error' => $message_error, 'code_error' => $code_error),
                array()
            );


        }
        else{
            $this->setCurrentRouter('router.theliastore');

            return $this->generateRedirectFromRoute(
                'theliastore.login.store.account',
                array(),
                array()
            );
        }

    }

    /**
     * @param $cart_id : the cart id
     * @param $item_id : the cart item id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cartDeleteItemAction($cart_id,$item_id){

        if(TheliaStore::isConnected() === 1 && $cart_id>0) {

            $api = TheliaStore::getApi();

            $session = new Session();
            $dataAccount = $session->get('storecustomer');

            $param = array();
            $param['cart_id'] = $cart_id;
            $param['item_id'] = $item_id;
            $param['customer_id'] = $dataAccount['ID'];
            //var_dump($param);
            list($status, $data) = $api->doPost('cart/' . $cart_id . '/item/' . $item_id . '/delete', $param);
            //var_dump($data);

            if($status == 200){
                $this->setCurrentRouter('router.theliastore');

                return $this->generateRedirectFromRoute(
                    'theliastore.extension.cart',
                    array(),
                    array()
                );
            }

            TheliaStore::extractError($error,$message,$data);

            $this->setCurrentRouter('router.theliastore');

            return $this->generateRedirectFromRoute(
                'theliastore.extension.cart',
                array('error'=>$error,'message'=>$message),
                array()
            );

        }

        $this->setCurrentRouter('router.theliastore');

        return $this->generateRedirectFromRoute(
            'theliastore.login.store.account',
            array(),
            array()
        );

    }

}