<?php
namespace TheliaStore\Loop;

use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use TheliaStore\TheliaStore;

class CommentLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument('id'),
            Argument::createIntListTypeArgument('customer'),
            Argument::createAnyTypeArgument('ref'),
            Argument::createIntTypeArgument('ref_id'),
            Argument::createIntTypeArgument('status'),
            Argument::createAnyTypeArgument('order')
        );
    }

    public function buildArray()
    {

        $api = TheliaStore::getApi();

        $param = array();
        if ($this->getLimit() != 0) {
            $param['limit'] = $this->getLimit();
        }

        $param['ref'] = $this->getRef();
        $param['ref_id'] = $this->getRefId();

        if (null === $param['ref'] || null === $param['ref_id']) {
            return array();
        }

        if ($this->getStatus() !== null) {
            $param['status'] = $this->getStatus();
        }

        if ($this->getOrder() !== null) {
            $param['order'] = $this->getOrder();
        }

        list($status, $data) = $api->doList('comments', $param);

        if ($status == 200) {
            return $data;
        }
        return array();
    }

    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $entry) {
            $row = new LoopResultRow();

            foreach ($entry as $key => $elm) {
                $row->set($key, $elm);
            }

            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}