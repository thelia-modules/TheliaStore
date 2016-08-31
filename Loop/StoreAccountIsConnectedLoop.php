<?php
namespace TheliaStore\Loop;

use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

/**
 * Class StoreAccountIsConnectedLoop
 * @package TheliaStore\Loop
 * {@inheritdoc}
 */
class StoreAccountIsConnectedLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection();
    }

    public function buildArray()
    {

        $session = new Session();
        $connected = $session->get('isconnected', null);
        if ($connected) {
            return array(1);
        }
        return array();
    }

    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $entry) {
            $row = new LoopResultRow();
            $row->set("CONNECTED", $entry);
            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}