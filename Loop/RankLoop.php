<?php
namespace TheliaStore\Loop;

use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use TheliaStore\TheliaStore;

class RankLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument("id", 0),
            Argument::createIntTypeArgument("object_id", 0),
            Argument::createAnyTypeArgument("object_type", '')
        );
    }

    public function buildArray()
    {

        $api = TheliaStore::getApi();

        if ($this->getId() != 0) {
            list($status, $data) = $api->doGet('ranks', $this->getId());
        } else {
            $param = array();
            if ($this->getLimit() != 0) {
                $param['limit'] = $this->getLimit();
            }

            if ($this->getObjectId() != 0) {
                $param['object_id'] = $this->getObjectId();
            }

            if ($this->getObjectType() != '') {
                $param['object_type'] = $this->getObjectType();
            }

            list($status, $data) = $api->doList('ranks', $param);
        }

        if ($status == 200) {
            return $data;
        }

        return array();
    }

    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $entry) {
            $row = new LoopResultRow();
            /*
            'ID'
            'RANK'
            'OBJECT_ID'
            'OBJECT_TYPE'
            'LOOP_COUNT'
            'LOOP_TOTAL'
            */
            foreach ($entry as $key => $elm) {
                $row->set($key, $elm);
            }

            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}