<?php
namespace TheliaStore\Loop;


use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use TheliaStore\TheliaStore;

use Thelia\Api\Client\Client;

class RankValueLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument("rank_id",0,true)
        );
    }

    public function buildArray(){

        if($this->getRankId() > 0){

            $api = TheliaStore::getApi();

            $param = array();

            $param['rank_id'] = $this->getRankId();

            //var_dump($param);
            list($status, $data) = $api->doList('ranks/' . $this->getRankId() . '/values',$param);

            //var_dump($status);
            //var_dump($data);

            if($status == 200){
                return $data;
            }

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
            'INTVALUE'
            'SUM_COUNT_VALUE'
            'PCINTVALUE'
            */
            foreach ($entry as $key => $elm) {
                $row->set($key, $elm);
            }

            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}