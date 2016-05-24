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

use Thelia\Api\Client\Client;

class StoreAccountAddressLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('customer',0,true)
        );
    }

    public function buildArray()
    {

        $customerId = $this->getCustomer();

        $api = TheliaStore::getApi();

        list($status, $data) = $api->doList('customers/'.$customerId.'/address');

        //var_dump($status);
        //var_dump($data);

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
            array (size=21)
              'ID' => int 1
              'LABEL' => string '' (length=0)
              'CUSTOMER' => int 1
              'TITLE' => int 1
              'COMPANY' => string '' (length=0)
              'FIRSTNAME' => string 'Thelia modif' (length=12)
              'LASTNAME' => string 'Thelia modif' (length=12)
              'ADDRESS1' => string 'cour des étoiles' (length=17)
              'ADDRESS2' => string 'rue des miracles' (length=16)
              'ADDRESS3' => string '' (length=0)
              'ZIPCODE' => string '63000' (length=5)
              'CITY' => string 'clermont-ferrand' (length=16)
              'COUNTRY' => int 64
              'STATE' => string '' (length=0)
              'PHONE' => string '' (length=0)
              'CELLPHONE' => string '0102030405' (length=10)
              'DEFAULT' => int 1
              'CREATE_DATE' =>
                array (size=3)
                  'date' => string '2016-04-22 14:32:42' (length=19)
                  'timezone_type' => int 3
                  'timezone' => string 'Europe/Paris' (length=12)
              'UPDATE_DATE' =>
                array (size=3)
                  'date' => string '2016-04-22 15:51:15' (length=19)
                  'timezone_type' => int 3
                  'timezone' => string 'Europe/Paris' (length=12)
              'LOOP_COUNT' => int 1
              'LOOP_TOTAL' => int 1
            */

            $row->set("ID", $entry['ID'])
                ->set("LABEL", $entry['LABEL'])
                ->set("CUSTOMER", $entry['CUSTOMER'])
                ->set("TITLE", $entry['TITLE'])
                ->set("COMPANY", $entry['COMPANY'])
                ->set("FIRSTNAME", $entry['FIRSTNAME'])
                ->set("LASTNAME", $entry['LASTNAME'])
                ->set("ADDRESS1", $entry['ADDRESS1'])
                ->set("ADDRESS2", $entry['ADDRESS2'])
                ->set("ADDRESS3", $entry['ADDRESS3'])
                ->set("ZIPCODE", $entry['ZIPCODE'])
                ->set("CITY", $entry['CITY'])
                ->set("COUNTRY", $entry['COUNTRY'])
                ->set("STATE", $entry['STATE'])
                ->set("PHONE", $entry['PHONE'])
                ->set("CELLPHONE", $entry['CELLPHONE'])
                ->set("DEFAULT", $entry['DEFAULT'])
                ->set("CREATE_DATE", $entry['CREATE_DATE'])
                ->set("UPDATE_DATE", $entry['UPDATE_DATE'])
                ->set("LOOP_COUNT", $entry['LOOP_COUNT'])
                ->set("LOOP_TOTAL", $entry['LOOP_TOTAL'])
            ;

            $loopResult->addRow($row);

        }

        return $loopResult;
    }
}