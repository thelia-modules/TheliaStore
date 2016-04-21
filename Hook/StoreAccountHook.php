<?php
namespace TheliaStore\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use TheliaStore\TheliaStore;

class StoreAccountHook extends BaseHook
{
    public function onMainTopbarTop(HookRenderEvent  $event){

        $isconnected = TheliaStore::isConnected();

        $html = $this->render('hook-onMainTopbarTop.html',array('isconnected'=>$isconnected));
        $event->add($html);
    }
}