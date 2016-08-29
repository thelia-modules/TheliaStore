<?php
namespace TheliaStore\Loop;

use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use TheliaStore\TheliaStore;

/**
 * Class DocumentLoop
 * @package TheliaStore\Loop
 * {@inheritdoc}
 * @method int getId()
 * @method string getApisource()
 * @method string getSource()
 * @method int getSourceId()
 */
class DocumentLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('id', 0),
            Argument::createAnyTypeArgument('apisource', ""),
            Argument::createAnyTypeArgument('source', ""),
            Argument::createIntTypeArgument('source_id', 0)
        );
    }

    public function buildArray()
    {
        $api = TheliaStore::getApi();
        $param = array();

        if ($this->getId() != 0) {
            $param['id'] = $this->getId();
        }

        list($status, $data) = $api->doList($this->getApisource() . '/' . $this->getSourceId() . '/documents', $param);

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