<?php
namespace TheliaStore\Controller\Admin;

use Symfony\Component\HttpFoundation\Cookie;
use Thelia\Api\Client\Client;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\Security\Token\TokenProvider;

class SellerController extends BaseAdminController
{
    public function defaultAction($seller_id){
        return $this->render('seller-detail', array('seller_id'=>$seller_id));
    }
}