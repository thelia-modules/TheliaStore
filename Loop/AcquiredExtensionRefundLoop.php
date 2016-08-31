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
 *
 * AcquiredExtensionRefundLoop
 * Check if an extension can be refund
 *
 * Class AcquiredExtensionRefundLoop
 * @package TheliaStore\Loop
 *
 * {@inheritdoc}
 * @method int getExtensionId()
 */
class AcquiredExtensionRefundLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('extension_id', null, true)
        );
    }

    public function buildArray()
    {
        $api = TheliaStore::getApi();

        $param = array();

        $param['extension_id'] = $this->getExtensionId();

        $session = new Session();
        $dataAccount = $session->get('storecustomer');
        $param['customer_id'] = $dataAccount['ID'];

        list($status, $data) = $api->doList('acerefund', $param);

        if ($status == 200) {
            return $data;
        }
        return array();
    }

    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $entry) {
            $row = new LoopResultRow();
            $row->set('REFUND', $entry);
            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}
