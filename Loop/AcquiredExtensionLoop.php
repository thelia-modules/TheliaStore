<?php
namespace TheliaStore\Loop;

use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use TheliaStore\Model\StoreExtensionQuery;
use TheliaStore\TheliaStore;

use Thelia\Api\Client\Client;

class AcquiredExtensionLoop extends BaseLoop implements PropelSearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('id', 0),
            Argument::createIntTypeArgument('extension_id', 0),
            Argument::createIntTypeArgument('product_id', 0)
        );
    }
    /**
     * this method returns a Propel ModelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria(){

        $search = StoreExtensionQuery::create();

        if($this->getId() != 0){
            $search->filterById($this->getId());
        }

        if($this->getExtensionId() != 0){
            $search->filterByExtensionId($this->getExtensionId());
        }

        if($this->getProductId() != 0){
            $search->filterByProductExtensionId($this->getProductId());
        }

        return $search;

    }

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var \TheliaStore\Model\StoreExtension $entry */
        foreach ($loopResult->getResultDataCollection() as $entry) {
            $row = new LoopResultRow();

            $row->set("ID", $entry->getId())
                ->set("CODE", $entry->getCode())
                ->set("EXTENSION_ID", $entry->getExtensionId())
                ->set("PRODUCT_EXTENSION_ID", $entry->getProductExtensionId())
                ->set("EXTENSION_NAME", $entry->getExtensionName())
                ->set("TOKEN", $entry->getToken())
                ->set("INSTALLATION_STATE", $entry->getInstallationState())
            ;

            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}