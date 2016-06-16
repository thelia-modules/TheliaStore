<?php
namespace TheliaStore\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\HttpFoundation\Session\Session;
use TheliaStore\TheliaStore;

class AdminCustomeHook extends BaseHook
{
    public function onExtensionGetFormPayment(HookRenderEvent $event)
    {

        //$html = $this->render('hook-onMainInTopMenuItems.html',array("admin_current_location"=>$event->getArgument('admin_current_location',null)));
        $order_id = $event->getArgument('orderid');
        $cart_type = $event->getArgument('carttype');
        $api = TheliaStore::getApi();
        $param = array();
        $param['order_id'] = $order_id;
        $param['cart_type'] = $cart_type;
        list($status, $data) = $api->doList('mangopay-paymentform', $param);
        //var_dump($data);
        $event->add($data['html']);
    }

    public function onExtensionChoseCartType(HookRenderEvent $event)
    {
        $api = TheliaStore::getApi();
        $param = array();
        list($status, $data) = $api->doList('mangopay-cart-type', $param);
        //var_dump($data);
        $event->add($data['html']);
    }

    public function onStoreError(HookRenderEvent $event)
    {
        $session = new Session();
        $store_error = $session->getFlashBag()->get('store_error', array());
        $html = '';
        foreach ($store_error as $message) {
            $html.= $this->render('hook-storeError.html', array('message' => $message));
        }
        $event->add($html);
    }
    /*
    public function onStoreCreateAccount(HookRenderEvent  $event){
        $api = TheliaStore::getApi();
        $param = array();
        list($status, $data) = $api->doList('store-account-creation',$param);
        $event->add($data['html']);
    }
    */
}