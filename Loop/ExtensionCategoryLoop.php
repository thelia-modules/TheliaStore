<?php
namespace TheliaStore\Loop;

use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use TheliaStore\TheliaStore;

class ExtensionCategoryLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('id', 0),
            Argument::createIntTypeArgument('parent', 0)
        );
    }

    public function buildArray()
    {
        $api = TheliaStore::getApi();
        $param = array();

        if ($this->getId() != 0) {
            $param['id'] = $this->getId();
        }
        if ($this->getParent() != 0) {
            $param['parent'] = $this->getParent();
        }

        list($status, $data) = $api->doList('categories', $param);

        if ($status == 200) {
            return $data;
        }

        return array();
    }

    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $entry) {
            $row = new LoopResultRow();

            $row->set("ID", $entry['ID'])
                ->set("IS_TRANSLATED", $entry['IS_TRANSLATED'])
                ->set("LOCALE", $entry['LOCALE'])
                ->set("TITLE", $entry['TITLE'])
                ->set("CHAPO", $entry['CHAPO'])
                ->set("DESCRIPTION", $entry['DESCRIPTION'])
                ->set("POSTSCRIPTUM", $entry['POSTSCRIPTUM'])
                ->set("PARENT", $entry['PARENT'])
                ->set("ROOT", $entry['ROOT'])
                ->set("URL", $entry['URL'])
                ->set("META_TITLE", $entry['META_TITLE'])
                ->set("META_DESCRIPTION", $entry['META_DESCRIPTION'])
                ->set("META_KEYWORDS", $entry['META_KEYWORDS'])
                ->set("POSITION", $entry['POSITION'])
                ->set("TEMPLATE", $entry['TEMPLATE'])
                ->set("DEFAULT_FOLDER", $entry['DEFAULT_FOLDER'])
                ->set("VISIBLE", $entry['VISIBLE'])
                ->set("VERSION", $entry['VERSION'])
                ->set("VERSION_DATE", $entry['VERSION_DATE'])
                ->set("LOOP_COUNT", $entry['LOOP_COUNT'])
                ->set("LOOP_TOTAL", $entry['LOOP_TOTAL']);

            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}