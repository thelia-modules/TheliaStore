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

class ExtensionVersionLoop extends BaseLoop implements ArraySearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('id', 0),
            Argument::createIntTypeArgument('extension_id', 0),
            Argument::createIntTypeArgument('product_extension_id', 0),
            Argument::createIntTypeArgument('category', 0),
            Argument::createAnyTypeArgument('feature_availability', ""),
            Argument::createIntTypeArgument('depth', 0),
            Argument::createIntTypeArgument('new'),
            Argument::createAnyTypeArgument('order', "")
        );
    }

    public function buildArray()
    {

        $api = TheliaStore::getApi();

        $session = new Session();
        $param = array();
        $param['lang'] = $session->getLang()->getId();

        /*
         * id or extension_id is mandatory
         */
        if ($this->getId() != 0) {
            list($status, $data) = $api->doGet('products', $this->getId(), $param);
        } elseif ($this->getExtensionId() != 0) {

            if ($this->getLimit() != 0) {
                $param['limit'] = $this->getLimit();
            }

            if ($this->getCategory() != 0) {
                $param['category'] = $this->getCategory();
            }

            if ($this->getFeatureAvailability() != "") {
                $param['feature_availability'] = $this->getFeatureAvailability();
            }

            if ($this->getDepth() != 0) {
                $param['depth'] = $this->getDepth();
            }

            if ($this->getOrder() != "") {
                $param['order'] = $this->getOrder();
            }

            if ($this->getExtensionId() != 0) {
                $param['extension_id'] = $this->getExtensionId();
            }

            if ($this->getProductExtensionId() != 0) {
                $param['product_extension_id'] = $this->getProductExtensionId();
            }

            $param['new'] = $this->getNew();

            list($status, $data) = $api->doList('extensions/' . $this->getExtensionId() . '/versions', $param);
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
            WEIGHT
            QUANTITY
            EAN_CODE
            BEST_PRICE
            BEST_PRICE_TAX
            BEST_TAXED_PRICE
            PRICE
            PRICE_TAX
            TAXED_PRICE
            PROMO_PRICE
            PROMO_PRICE_TAX
            TAXED_PROMO_PRICE
            IS_PROMO
            IS_NEW
            PRODUCT_SALE_ELEMENT
            PSE_COUNT
            EXTENSION_ID
            VERSION_ID
            STATE_ID
            NUM_VERSION
            DOWNLOAD_COUNT
            UNISTALL_COUNT
            ID
            REF
            IS_TRANSLATED
            LOCALE
            TITLE
            CHAPO
            DESCRIPTION
            POSTSCRIPTUM
            URL
            META_TITLE
            META_DESCRIPTION
            META_KEYWORDS
            POSITION
            VIRTUAL
            VISIBLE
            TEMPLATE
            DEFAULT_CATEGORY
            TAX_RULE_ID
            BRAND_ID
            SHOW_ORIGINAL_PRICE
            VERSION
            VERSION_DATE
            VERSION_AUTHOR
            CREATE_DATE
            UPDATE_DATE
            LOOP_COUNT
            LOOP_TOTAL
            */


            /*
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
                ->set("EXTENSION_ID",$entry['PSE_COUNT'])
                ->set("CUSTOMER_BENEFIT",$entry['CUSTOMER_BENEFIT'])
                ->set("MERCHANT_BENEFIT",$entry['MERCHANT_BENEFIT'])
                ->set("IS_PUBLISHED",$entry['IS_PUBLISHED'])
                ->set("HAS_FAQ",$entry['HAS_FAQ'])
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
            */
            $loopResult->addRow($row);
        }

        return $loopResult;
    }
}