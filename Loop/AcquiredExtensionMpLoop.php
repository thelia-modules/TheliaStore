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
 * Class AcquiredExtensionMpLoop
 * @package TheliaStore\Loop
 * {@inheritdoc}
 * @method int getExtensionId()
 */
class AcquiredExtensionMpLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('extension_id')
        );
    }

    public function buildArray()
    {
        $api = TheliaStore::getApi();

        $param = array();

        if ($this->getExtensionId() !== null) {
            $param['extension_id'] = $this->getExtensionId();
        }

        $session = new Session();
        $dataAccount = $session->get('storecustomer');
        $param['customer_id'] = $dataAccount['ID'];

        list($status, $data) = $api->doList('aceacquired', $param);

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
            array (size=11)
              'ID' => int 7
              'CUSTOMER_ID' => int 1
              'EXTENSION_ID' => int 8
              'ORDER_ID' => int 68
              'ORDER_PRODUCT_ID' => int 61
              'INSTALLATION_STATE' => int 1
              'TOKEN' => string '320971108acb748c7bd75e4519ea9f65' (length=32)
              'CREATEDAT' =>
                array (size=3)
                  'date' => string '2016-07-07 10:45:57' (length=19)
                  'timezone_type' => int 3
                  'timezone' => string 'Europe/Paris' (length=12)
              'UPDATEDAT' =>
                array (size=3)
                  'date' => string '2016-07-07 10:45:57' (length=19)
                  'timezone_type' => int 3
                  'timezone' => string 'Europe/Paris' (length=12)
              'LOOP_COUNT' => int 4
              'LOOP_TOTAL' => int 4
            */
            //var_dump($entry);
            //var_dump($entry['CREATEDAT']['date']);
            $createDate = new \DateTime($entry['CREATEDAT']['date']);
            $nowDate = new \DateTime("now");
            $interval = $createDate->diff($nowDate);

            foreach ($entry as $key => $elm) {
                $row->set($key, $elm);
            }
            $row->set('DAYS',$interval->format('%a'));

            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}