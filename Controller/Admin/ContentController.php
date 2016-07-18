<?php
namespace TheliaStore\Controller\Admin;

use Thelia\Controller\Admin\BaseAdminController;

class ContentController extends BaseAdminController
{
    public function defaultAction($content_id)
    {
        return $this->render('content-detail', array('content_id' => $content_id));
    }
}
