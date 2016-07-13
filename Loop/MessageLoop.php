<?php
namespace TheliaStore\Loop;

use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use TheliaStore\TheliaStore;

class MessageLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument("id", 0),
            Argument::createIntTypeArgument("id_object", 0),
            Argument::createAnyTypeArgument("type_object", ''),
            Argument::createAnyTypeArgument("type_message", ''),
            Argument::createAnyTypeArgument("type_sender", ''),
            Argument::createIntTypeArgument("id_sender", 0)
        );
    }

    public function buildArray()
    {

        $api = TheliaStore::getApi();

        if ($this->getId() != 0) {
            list($status, $data) = $api->doGet('msmessages', $this->getId());
        } else {
            $param = array();
            if ($this->getLimit() != 0) {
                $param['limit'] = $this->getLimit();
            }

            if ($this->getIdObject() != 0) {
                $param['id_object'] = $this->getIdObject();
            }

            if ($this->getTypeObject() != '') {
                $param['type_object'] = $this->getTypeObject();
            }

            /*
            if ($this->getTypeMessage() != '') {
               $param['type_message'] = $this->getTypeMessage();
            }
            */

            if ($this->getTypeSender() != '') {
                $param['type_sender'] = $this->getTypeSender();
            }

            if ($this->getIdSender() != 0) {
                $param['id_sender'] = $this->getIdSender();
            }

            $param['type_message'] = 'customer';

            list($status, $data) = $api->doList('msmessages', $param);
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
            /*
            'ID'
            'LOCALE'
            'TITLE'
            'CHAPO'
            'DESCRIPTION'
            'POSTSCRIPTUM'
            'ID_OBJECT'
            'TYPE_OBJECT'
            'TYPE_MESSAGE'
            'TITLE_SENDER'
            'URL_SENDER'
            'TYPE_SENDER'
            'ID_SENDER'
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