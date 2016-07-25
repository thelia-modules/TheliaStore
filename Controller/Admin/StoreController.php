<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/
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
