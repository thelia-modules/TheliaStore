<?php
namespace TheliaStore\Form;

use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Thelia\Core\Translation\Translator;
use Thelia\Form\AddressCountryValidationTrait;
use Thelia\Form\BaseForm;
use Thelia\Model\ConfigQuery;
use TheliaStore\Model\Map\StoreConfigTableMap;
use TheliaStore\Model\StoreConfigQuery;
use TheliaStore\TheliaStore;

class StoreConfigForm extends BaseForm
{
    protected function buildForm()
    {
        $this->formBuilder
            ->add("api_token", "text", array(
                "constraints" => array(
                    new NotBlank()
                ),
                "label" => Translator::getInstance()->trans('api_token', [], TheliaStore::BO_DOMAIN_NAME),
                "label_attr" => array(
                    "for" => "api_token"
                ),
                'attr' => [
                    'placeholder' => Translator::getInstance()->trans('api_token', [], TheliaStore::BO_DOMAIN_NAME),
                ]
            ))
            ->add("api_key", "text", array(
                "constraints" => array(
                    new NotBlank()
                ),
                "label" => Translator::getInstance()->trans('api_key', [], TheliaStore::BO_DOMAIN_NAME),
                "label_attr" => array(
                    "for" => "api_key"
                ),
                'attr' => [
                    'placeholder' => Translator::getInstance()->trans('api_key', [], TheliaStore::BO_DOMAIN_NAME),
                ]
            ))
            ->add("api_url", "text", array(
                "constraints" => array(
                    new NotBlank()
                ),
                "label" => Translator::getInstance()->trans('api_url', [], TheliaStore::BO_DOMAIN_NAME),
                "label_attr" => array(
                    "for" => "api_url"
                ),
                'attr' => [
                    'placeholder' => Translator::getInstance()->trans('api_url', [], TheliaStore::BO_DOMAIN_NAME),
                ]
            ));

        $myObject = StoreConfigQuery::create()->findOne();
        if ($myObject) {
            $arrayData = $myObject->toArray(StoreConfigTableMap::TYPE_FIELDNAME);

            $this->formBuilder
                ->add("id", "hidden", array(
                    "constraints" => array(
                        new NotBlank()
                    )
                ))
                ->setData($arrayData);
        }

    }

    public function getName()
    {
        return "store_config";
    }
}