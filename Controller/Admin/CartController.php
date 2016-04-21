<?php
namespace TheliaStore\Controller\Admin;

use Symfony\Component\HttpFoundation\Cookie;
use Thelia\Api\Client\Client;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Security\Token\TokenProvider;
use Thelia\Model\ConfigQuery;
use TheliaStore\Model\StoreExtension;
use TheliaStore\TheliaStore;

class CartController extends BaseAdminController
{

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
            /*
            $status = 200;
            $data = array(
                'order' => 188,
                'products' => array(6)
            );
            */

            //var_dump($param);
            list($status, $data) = $api->doPost('cart/'.$cartId.'/validate',$param);

            /*
             * data : [
                    'order' =>  $order_id,
                    'products' => [][
                        'product_id' => $product->getId(),
                        'product_title' => $productI18n->getTitle()
                        'token' => $token,
                        'code' => $extension->getCode()
                    ]
                ],
             */

            //var_dump($status);
            //var_dump($data);

            if($status == 200){

                /*
                 * Ajout dans store_extension
                 */
                $tabProducts = $data['products'];
                foreach($tabProducts as $product){
                    $myExtension = new StoreExtension();
                    $myExtension->setExtensionId($product['product_id'])
                        ->setToken($product['token'])
                        ->setCode($product['code'])
                        ->setExtensionName($product['product_title'])
                        ->setInstallationState(1)
                        ->save();
                }

                $this->setCurrentRouter('router.theliastore');
                return $this->generateRedirectFromRoute(
                    'theliastore.cart.download',
                    array(),
                    array('downloadproduct'=>serialize($data['products']))
                );

                //return $this->render('store-cart-validate', array('category_id'=>0, 'sub_category_id'=>0, 'downloadproduct'=>$data['products']));
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

    public function cartDeleteItemAction($cart_id,$item_id){

        if(TheliaStore::isConnected() === 1 && $cart_id>0) {

            $api = TheliaStore::getApi();

            $session = new Session();
            $dataAccount = $session->get('storecustomer');

            $param = array();
            $param['cart_id'] = $cart_id;
            $param['item_id'] = $item_id;
            $param['customer_id'] = $dataAccount['ID'];

            list($status, $data) = $api->doPost('cart/' . $cart_id . '/item/' . $item_id . '/delete', $param);

            if($status == 200){
                $this->setCurrentRouter('router.theliastore');

                return $this->generateRedirectFromRoute(
                    'theliastore.extension.cart',
                    array(),
                    array()
                );
            }
            $this->setCurrentRouter('router.theliastore');

            return $this->generateRedirectFromRoute(
                'theliastore.extension.cart',
                array(),
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