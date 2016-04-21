<?php
/**
 * Created by PhpStorm.
 * User: E-FUSION-JULIEN
 * Date: 04/04/2016
 * Time: 09:29
 */

namespace TheliaStore\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class IncludeTheliaStore extends BaseHook
{
    public function onMainHeadCss(HookRenderEvent  $event){
        $content = $this->addCSS('assets/css/theliastore-icon.css');
        $content.= $this->addCSS('assets/css/theliastore.css');
        $content.= $this->addCSS('assets/css/theliastore-star-rating.min.css');
        $event->add($content);
    }
}