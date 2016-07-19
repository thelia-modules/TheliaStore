<?php
namespace TheliaStore\Loop;

use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use TheliaStore\TheliaStore;

class StoreAccountLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection();
    }

    public function buildArray()
    {

        $session = new Session();
        $dataAccount = $session->get('storecustomer');

        if ($dataAccount && is_array($dataAccount)) {
            $api = TheliaStore::getApi();
            list($status, $data) = $api->doGet('customers', $dataAccount['ID']);

            if ($status == 200) {
                var_dump($data);
                return $data;
            }

            return array();
        }
        return array();
    }

    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $entry) {

            $row = new LoopResultRow();
            /*
            array (size=14)
              'ID' => int 1
              'REF' => string 'CUS000000000001' (length=15)
              'TITLE' => int 1
              'FIRSTNAME' => string 'Julien' (length=6)
              'LASTNAME' => string 'VIGOUROUX' (length=9)
              'EMAIL' => string 'jvigouroux@openstudio.fr' (length=24)
              'RESELLER' => string '' (length=0)
              'SPONSOR' => string '' (length=0)
              'DISCOUNT' => string '' (length=0)
              'NEWSLETTER' => string '0' (length=1)
              'CREATE_DATE' =>
                array (size=3)
                  'date' => string '2016-04-22 14:32:42' (length=19)
                  'timezone_type' => int 3
                  'timezone' => string 'Europe/Paris' (length=12)
              'UPDATE_DATE' =>
                array (size=3)
                  'date' => string '2016-04-22 14:33:09' (length=19)
                  'timezone_type' => int 3
                  'timezone' => string 'Europe/Paris' (length=12)
              'LOOP_COUNT' => int 1
              'LOOP_TOTAL' => int 1
            */
            $row->set("ID", $entry['ID'])
                ->set("REF", $entry['REF'])
                ->set("TITLE", $entry['TITLE'])
                ->set("FIRSTNAME", $entry['FIRSTNAME'])
                ->set("LASTNAME", $entry['LASTNAME'])
                ->set("EMAIL", $entry['EMAIL'])
                ->set("DISCOUNT", $entry['DISCOUNT'])
                ->set("NEWSLETTER", $entry['NEWSLETTER'])
                ->set("CREATE_DATE", $entry['CREATE_DATE']['date'])
                ->set("UPDATE_DATE", $entry['UPDATE_DATE']['date'])
                ->set("LOOP_COUNT", $entry['LOOP_COUNT'])
                ->set("LOOP_TOTAL", $entry['LOOP_TOTAL']);
            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}