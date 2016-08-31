<?php
namespace TheliaStore\Loop;

use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use TheliaStore\TheliaStore;

/**
 * Class RankCustomerLoop
 * @package TheliaStore\Loop
 * {@inheritdoc}
 * @method int getRankId()
 */
class RankCustomerLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument("rank_id", 0, true)
        );
    }

    public function buildArray()
    {

        $api = TheliaStore::getApi();
        if (TheliaStore::isConnected() === 1) {
            $param = array();

            $param['rank_id'] = $this->getRankId();

            $session = new Session();
            $dataAccount = $session->get('storecustomer');
            $param['customer_id'] = $dataAccount['ID'];

            list($status, $data) = $api->doList('ranks/' . $this->getRankId() . '/customers', $param);

            if ($status == 200) {
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
            */
            foreach ($entry as $key => $elm) {
                $row->set($key, $elm);
            }

            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}