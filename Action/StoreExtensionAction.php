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
namespace TheliaStore\Action;

use Propel\Runtime\Propel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TheliaStore\Event\StoreExtensionEvent;
use TheliaStore\Event\TheliaStoreEvents;
use TheliaStore\Model\Map\StoreExtensionTableMap;
use TheliaStore\Model\StoreExtension;

class StoreExtensionAction implements EventSubscriberInterface
{

    public function create(StoreExtensionEvent $event)
    {
        $con = Propel::getWriteConnection(StoreExtensionTableMap::DATABASE_NAME);
        $con->beginTransaction();

        try {
            $myExtension = new StoreExtension();
            $myExtension->setExtensionId($event->getExtensionId())
                ->setProductExtensionId($event->getProductExtensionId())
                ->setToken($event->getToken())
                ->setCode($event->getCode())
                ->setExtensionName($event->getExtensionName())
                ->setInstallationState($event->getInstallationState())
                ->save($con);

            $con->commit();

            $event->setStoreExtension($myExtension);

        } catch (\Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            TheliaStoreEvents::STORE_EXTENSION_CREATE => array("create", 128),
        );
    }
}