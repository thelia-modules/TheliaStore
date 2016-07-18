<?php
namespace TheliaStore\Controller\Admin;

use Thelia\Controller\Admin\BaseAdminController;

class SellerController extends BaseAdminController
{
    public function defaultAction($seller_id)
    {
        return $this->render('seller-detail', array('seller_id' => $seller_id));
    }
}
