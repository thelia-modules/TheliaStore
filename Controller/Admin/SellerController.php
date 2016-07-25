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

class SellerController extends BaseAdminController
{
    public function defaultAction($seller_id)
    {
        return $this->render('seller-detail', array('seller_id' => $seller_id));
    }
}
