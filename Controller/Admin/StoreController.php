<?php
namespace TheliaStore\Controller\Admin;

use Thelia\Controller\Admin\BaseAdminController;

class StoreController extends BaseAdminController
{
    public function defaultAction()
    {
        return $this->render('store-index', array('category_id' => 0, 'sub_category_id' => 0));
    }

    public function categoryListAction($category_id, $sub_category_id = 0)
    {
        return $this->render(
            'store-category',
            array('category_id' => $category_id, 'sub_category_id' => $sub_category_id)
        );
    }
}
