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

class ExtensionLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('id', 0),
            Argument::createAnyTypeArgument('ids',''),
            Argument::createAnyTypeArgument('category',''),
            Argument::createAnyTypeArgument('feature_availability', ""),
            Argument::createIntTypeArgument('depth', 0),
            Argument::createIntTypeArgument('new'),
            Argument::createIntTypeArgument('promo'),
            Argument::createAnyTypeArgument('order', ""),
            Argument::createIntTypeArgument('seller', 0),
            Argument::createIntTypeArgument('exclude_category', 0)
        );
    }

    public function buildArray(){

        $api = TheliaStore::getApi();

        //Les extensions disposent de la caractéristique type = extension
        //la feature type d'id 1, et la valeur "extension" d'id 1

        if($this->getId()!=0){
            list($status, $data) = $api->doGet('extensions',$this->getId());
        }
        else{
            $param = array();

            if($this->getIds()!='')
                $param['id'] = $this->getIds();

            if($this->getLimit()!=0)
                $param['limit'] = $this->getLimit();

            if($this->getCategory()!='')
                $param['category'] = $this->getCategory();

            if($this->getFeatureAvailability()!="")
                $param['feature_availability'] = $this->getFeatureAvailability();

            if($this->getDepth()!=0)
                $param['depth'] = $this->getDepth();

            if($this->getOrder()!="")
                $param['order'] = $this->getOrder();

            if($this->getSeller()!=0)
                $param['seller'] = $this->getSeller();

            if($this->getExcludeCategory()!=0)
                $param['exclude_category'] = $this->getExcludeCategory();

            $param['new'] = $this->getNew();
            $param['promo'] = $this->getPromo();

            //var_dump($param);

            list($status, $data) = $api->doList('extensions',$param);
        }

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
            /*
             * TODO : tronquer le chapo à n. caractéres
             */
            $row->set("ID", $entry['ID'])
                ->set("REF", $entry['REF'])
                ->set("WEIGHT",$entry['WEIGHT'])
                ->set("QUANTITY",$entry['QUANTITY'])
                ->set("EAN_CODE",$entry['EAN_CODE'])
                ->set("BEST_PRICE",$entry['BEST_PRICE'])
                ->set("BEST_PRICE_TAX",$entry['BEST_PRICE_TAX'])
                ->set("BEST_TAXED_PRICE",$entry['BEST_TAXED_PRICE'])
                ->set("PRICE",$entry['PRICE'])
                ->set("PRICE_TAX",$entry['PRICE_TAX'])
                ->set("TAXED_PRICE",$entry['TAXED_PRICE'])
                ->set("PROMO_PRICE",$entry['PROMO_PRICE'])
                ->set("PROMO_PRICE_TAX",$entry['PROMO_PRICE_TAX'])
                ->set("TAXED_PROMO_PRICE",$entry['TAXED_PROMO_PRICE'])
                ->set("IS_PROMO",$entry['IS_PROMO'])
                ->set("IS_NEW",$entry['IS_NEW'])
                ->set("PRODUCT_SALE_ELEMENT",$entry['PRODUCT_SALE_ELEMENT'])
                ->set("PSE_COUNT",$entry['PSE_COUNT'])
                ->set("EXTENSION_ID",$entry['EXTENSION_ID'])
                ->set("CUSTOMER_BENEFIT",$entry['CUSTOMER_BENEFIT'])
                ->set("MERCHANT_BENEFIT",$entry['MERCHANT_BENEFIT'])
                ->set("IS_PUBLISHED",$entry['IS_PUBLISHED'])
                ->set("HAS_FAQ",$entry['HAS_FAQ'])
                ->set("SELLER_ID",$entry['SELLER_ID'])
                ->set("DOWNLOAD_COUNT", $entry['DOWNLOAD_COUNT'])
                ->set("LAST_VERSION", $entry['LAST_VERSION'])
                ->set("LAST_VERSION_ID", $entry['LAST_VERSION_ID'])
                ->set("LAST_VERSION_EXTENSION_ID", $entry['LAST_VERSION_EXTENSION_ID'])
                ->set("IS_TRANSLATED", $entry['IS_TRANSLATED'])
                ->set("LOCALE", $entry['LOCALE'])
                ->set("TITLE", $entry['TITLE'])
                ->set("CHAPO", $entry['CHAPO'])
                ->set("DESCRIPTION", $entry['DESCRIPTION'])
                ->set("POSTSCRIPTUM", $entry['POSTSCRIPTUM'])
                ->set("URL", $entry['URL'])
                ->set("META_TITLE", $entry['META_TITLE'])
                ->set("META_DESCRIPTION", $entry['META_DESCRIPTION'])
                ->set("META_KEYWORDS", $entry['META_KEYWORDS'])
                ->set("POSITION", $entry['POSITION'])
                ->set("DEFAULT_FOLDER", $entry['DEFAULT_FOLDER'])
                ->set("VISIBLE", $entry['VISIBLE'])
                ->set("VERSION", $entry['VERSION'])
                ->set("VERSION_DATE", $entry['VERSION_DATE'])
                ->set("LOOP_COUNT", $entry['LOOP_COUNT'])
                ->set("LOOP_TOTAL", $entry['LOOP_TOTAL'])
                ->set("VIRTUAL",$entry['VIRTUAL'])
                ->set("VISIBLE",$entry['VISIBLE'])
                ->set("TEMPLATE",$entry['TEMPLATE'])
                ->set("DEFAULT_CATEGORY",$entry['DEFAULT_CATEGORY'])
                ->set("TAX_RULE_ID",$entry['TAX_RULE_ID'])
                ->set("BRAND_ID",$entry['BRAND_ID'])
                ->set("SHOW_ORIGINAL_PRICE",$entry['SHOW_ORIGINAL_PRICE'])
            ;

            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}