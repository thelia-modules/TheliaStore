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
 * Class SellerLoop
 * @package TheliaStore\Loop
 * {@inheritdoc}
 * @method int getId()
 */
class SellerLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('id', 0)
        );
    }

    public function buildArray()
    {

        $api = TheliaStore::getApi();

        if ($this->getId() != 0) {
            list($status, $data) = $api->doGet('sellers', $this->getId());
        } else {
            $param = array();

            list($status, $data) = $api->doList('sellers', $param);
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

            foreach ($entry as $key => $elm) {
                $row->set($key, $elm);
            }
            /*
            ID
            LOGO
            TITLE
            DESCRIPTION
            CUSTOMERID
            TITLEID
            COMPANY
            FIRSTNAME
            LASTNAME
            ADDRESS1
            ADDRESS2
            ADDRESS3
            ZIPCODE
            CITY
            COUNTRYID
            STATEID
            PHONE
            CELLPHONE
            CREATEDAT
            UPDATEDAT
            LOOP_COUNT
            LOOP_TOTAL
            */

            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}