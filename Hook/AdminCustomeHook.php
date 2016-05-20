<?php
namespace TheliaStore\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use TheliaStore\TheliaStore;

class AdminCustomeHook extends BaseHook
{
    public function onExtensionGetFormPayment(HookRenderEvent  $event){

        //$html = $this->render('hook-onMainInTopMenuItems.html',array("admin_current_location"=>$event->getArgument('admin_current_location',null)));
        $order_id = $event->getArgument('orderid');
        $cart_type = $event->getArgument('carttype');
        $api = TheliaStore::getApi();
        $param = array();
        $param['order_id'] = $order_id;
        $param['cart_type'] = $cart_type;
        list($status, $data) = $api->doList('mangopay-paymentform',$param);
        //var_dump($data);
        $event->add($data['html']);
    }

    public function onExtensionChoseCartType(HookRenderEvent  $event){
        $api = TheliaStore::getApi();
        $param = array();
        list($status, $data) = $api->doList('mangopay-cart-type',$param);
        //var_dump($data);
        $event->add($data['html']);
    }

}