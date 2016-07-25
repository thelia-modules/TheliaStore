<?php
namespace TheliaStore\Controller\Admin;

use Symfony\Component\HttpFoundation\Cookie;
use Thelia\Api\Client\Client;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Security\Token\TokenProvider;
use Thelia\Model\ConfigQuery;
use TheliaStore\Event\StoreExtensionEvent;
use TheliaStore\Event\TheliaStoreEvents;
use TheliaStore\Model\StoreExtensionQuery;
use TheliaStore\Model\StoreExtension;
use TheliaStore\TheliaStore;

class CartController extends BaseAdminController
{

    public function cartAction()
    {
        return $this->render('store-cart', array('category_id' => 0, 'sub_category_id' => 0));
    }

    public function cartGetFormPayment()
    {
        $order_id = $this->getRequest()->get('order_id');
        $cartType = $this->getRequest()->get('cartType');
        return $this->render('hook-getFormPayment', array('order_id' => $order_id, 'cartType' => $cartType));
    }

    public function cartPaymentAction($cart_id)
    {
        $urlpayment = $this->getRequest()->get('urlpayment');
        $order_id = $this->getRequest()->get('order_id');
        return $this->render(
            'store-cart-payment',
            array('cart_id' => $cart_id, 'urlpayment' => $urlpayment, 'order_id' => $order_id)
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response|Response
     */
    public function validateAction()
    {

        $cartId = $this->getRequest()->get('cart_id');
        $cartType = $this->getRequest()->get('CardType');
        if (TheliaStore::isConnected() === 1 && $cartId > 0) {
            $api = TheliaStore::getApi();

            $session = new Session();
            $dataAccount = $session->get('storecustomer');

            $param = array();
            $param['cart_id'] = $cartId;
            $param['customer_id'] = $dataAccount['ID'];
            $param['customer_domain'] = ConfigQuery::read('url_site');

            list($status, $data) = $api->doPost('cart/' . $cartId . '/validate', $param);

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

            if ($status == 200) {
                $urlPayment = $data['urlpayment'];
                $amount = $data['amount'];
                $order = $data['order'];
                $tabProducts = $data['products'];

                /*
                 * On place la liste des produits à télécharger en session
                 */
                $sTabProducts = serialize($tabProducts);
                $session->set('productsToDownload', $sTabProducts);
                $session->save();

                /*
                 * On test l'url de payment :
                 * vide : les modules sont gratuit, on télécharge directement
                 * non vide : c'est qu'on doit faire payer l'utilisateur
                 */
                if ($urlPayment === '') {
                    $this->addStoreExtensions($tabProducts);

                    $this->setCurrentRouter('router.theliastore');
                    return $this->generateRedirectFromRoute(
                        'theliastore.cart.download',
                        array(),
                        array()
                    );
                } else {
                    /*
                     * Affichage du formulaire de paiement
                     */
                    return $this->render(
                        'store-cart-payment',
                        array(
                            'cart_id' => $cartId,
                            'cartType' => $cartType,
                            'urlpayment' => $urlPayment,
                            'order_id' => $order,
                            'downloadproduct' => $sTabProducts
                        )
                    );
                }


            } else {
                $this->setCurrentRouter('router.theliastore');

                return $this->generateRedirectFromRoute(
                    'theliastore.extension.cart',
                    array(),
                    array()
                );

            }

        } else {
            $this->setCurrentRouter('router.theliastore');

            return $this->generateRedirectFromRoute(
                'theliastore.login.store.account',
                array(),
                array()
            );
        }

    }

    public function cartDownloadAction()
    {
        /*
         * Les produits à télécharger sont normalement en session
         * Si ce n'est pas le cas, on le recupére dans la requête
         */
        $session = new Session();
        $downloadproduct = unserialize($session->get('productsToDownload'));
        if (empty($downloadproduct)) {
            $downloadproduct = unserialize($this->getRequest()->get('downloadproduct'));
        }
        return $this->render(
            'store-cart-validate',
            array('category_id' => 0, 'sub_category_id' => 0, 'downloadproduct' => $downloadproduct)
        );
    }


    public function orderDownloadAction($order_id)
    {
        /*
         * Les produit à télécharger sont normalement en session
         * Si ce n'est pas le cas, on le recupére dans la requête
         */
        $session = new Session();
        $downloadproduct = unserialize($session->get('productsToDownload'));
        if (empty($downloadproduct)) {
            $downloadproduct = unserialize($this->getRequest()->get('downloadproduct'));
        }
        $this->addStoreExtensions($downloadproduct);
        return $this->render(
            'store-cart-validate',
            array('category_id' => 0, 'sub_category_id' => 0, 'downloadproduct' => $downloadproduct)
        );
    }

    /**
     * @param $product_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cartAddAction($product_id)
    {

        if (TheliaStore::isConnected() === 1) {
            $api = TheliaStore::getApi();

            $param = array();
            $param['product'] = $product_id;
            $param['customer_id'] = $this->getRequest()->get('customer');
            $param['customer_domain'] = ConfigQuery::read('url_site');

            list($status, $data) = $api->doPost('product-extensions/' . $product_id . '/addcart', $param);

            TheliaStore::extractError($data);

            $this->setCurrentRouter('router.theliastore');

            return $this->generateRedirectFromRoute(
                'theliastore.extension.cart',
                array(),
                array()
            );

        } else {
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
    public function cartDeleteItemAction($cart_id, $item_id)
    {
        if (TheliaStore::isConnected() === 1 && $cart_id > 0) {
            $api = TheliaStore::getApi();

            $session = new Session();
            $dataAccount = $session->get('storecustomer');

            $param = array();
            $param['cart_id'] = $cart_id;
            $param['item_id'] = $item_id;
            $param['customer_id'] = $dataAccount['ID'];

            list($status, $data) = $api->doPost('cart/' . $cart_id . '/item/' . $item_id . '/delete', $param);

            if ($status == 200) {
                $this->setCurrentRouter('router.theliastore');

                return $this->generateRedirectFromRoute(
                    'theliastore.extension.cart',
                    array(),
                    array()
                );
            }

            TheliaStore::extractError($data);

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

    /**
     * @param $tabProducts
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function addStoreExtensions($tabProducts)
    {
        foreach ($tabProducts as $product) {
            $myExtension = StoreExtensionQuery::create()->findOneByExtensionId($product['extension_id']);
            if (!$myExtension) {
                $extensionEvent = new StoreExtensionEvent(
                    $product['extension_id'],
                    $product['product_id'],
                    $product['token'],
                    $product['code'],
                    $product['product_title'],
                    1
                );

                $this->dispatch(TheliaStoreEvents::STORE_EXTENSION_CREATE, $extensionEvent);
            }
        }
    }
}
