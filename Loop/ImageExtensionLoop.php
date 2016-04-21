<?php
namespace TheliaStore\Loop;


use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use TheliaStore\TheliaStore;

use Thelia\Api\Client\Client;

class ImageExtensionLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('id', 0),
            Argument::createIntTypeArgument('product', 0),
            Argument::createIntTypeArgument('width', 0),
            Argument::createIntTypeArgument('height', 0),
            Argument::createAnyTypeArgument('resize_mode', ""),
            Argument::createAnyTypeArgument('allow_zoom', ""),
            Argument::createAnyTypeArgument('background_color', "")
        );
    }

    public function buildArray(){

        $api = TheliaStore::getApi();

        $param = array();


        if($this->getId()!=0){
            $param['id'] = $this->getId();
        }
        /*
        if($this->getProduct()!=0){
            $param['product'] = $this->getProduct();
        }
        */
        if($this->getWidth()!=0){
            $param['width'] = $this->getWidth();
        }
        if($this->getHeight()!=0){
            $param['height'] = $this->getHeight();
        }
        if($this->getResizeMode()!=""){
            $param['resize_mode'] = $this->getResizeMode();
        }
        if($this->getAllowZoom()!=""){
            $param['allow_zoom'] = $this->getAllowZoom();
        }
        if($this->getBackgroundColor()!=""){
            $param['background_color'] = $this->getBackgroundColor();
        }
        //var_dump($param);
        list($status, $data) = $api->doList('products/'.$this->getProduct().'/images',$param);
        //list($status, $data) = $api->doList('products/1/images',array());
        //var_dump($status);
        //var_dump($data);
        if($status == 200){
            return $data;
        }
        return array();
    }

    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $entry) {
            $row = new LoopResultRow();
            //var_dump($entry);
            $row->set("ID", $entry['ID'])
                ->set("LOCALE", $entry['LOCALE'])
                ->set("ORIGINAL_IMAGE_PATH", $entry['ORIGINAL_IMAGE_PATH'])
                ->set("TITLE", $entry['TITLE'])
                ->set("CHAPO", $entry['CHAPO'])
                ->set("DESCRIPTION", $entry['DESCRIPTION'])
                ->set("POSTSCRIPTUM", $entry['POSTSCRIPTUM'])
                ->set("VISIBLE", $entry['VISIBLE'])
                ->set("OBJECT_TYPE", $entry['OBJECT_TYPE'])
                ->set("OBJECT_ID", $entry['OBJECT_ID'])
                ->set("IMAGE_URL", $entry['IMAGE_URL'])
                ->set("ORIGINAL_IMAGE_URL", $entry['ORIGINAL_IMAGE_URL'])
                ->set("IMAGE_PATH", $entry['IMAGE_PATH'])
                ->set("POSITION", $entry['POSITION'])
                ->set("VISIBLE", $entry['VISIBLE'])
                ->set("VERSION", $entry['VERSION'])
                ->set("VERSION_DATE", $entry['VERSION_DATE'])
                ->set("LOOP_COUNT", $entry['LOOP_COUNT'])
                ->set("LOOP_TOTAL", $entry['LOOP_TOTAL'])
            ;


            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}