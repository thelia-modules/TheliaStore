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
 * Class FaqQuestionLoop
 * @package TheliaStore\Loop
 * {@inheritdoc}
 * @method int getId()
 * @method int getProductId()
 */
class FaqQuestionLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('id'),
            Argument::createIntTypeArgument('product_id')
        );
    }

    public function buildArray()
    {

        $api = TheliaStore::getApi();

        $param = array();

        if ($this->getLimit() != 0) {
            $param['limit'] = $this->getLimit();
        }

        $id = $this->getId();
        if (null !== $id) {
            $param['id'] = $id;
        }

        $productId = $this->getProductId();
        if (null !== $productId) {
            $param['product_id'] = $productId;
        }

        list($status, $data) = $api->doList('faq/questions', $param);

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