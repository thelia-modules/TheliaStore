<?php
namespace TheliaStore\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class StoreMenuHook extends BaseHook
{
    public function onMainTopbarBottom(HookRenderEvent  $event){
        //$html = $this->render('hook-onMainTopbarBottom.html');
        //$event->add($html);
    }
    public function onMainInTopMenuItems(HookRenderEvent  $event){
        $html = $this->render('hook-onMainInTopMenuItems.html',array("admin_current_location"=>$event->getArgument('admin_current_location',null)));
        $event->add($html);
    }
}