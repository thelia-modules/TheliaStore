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

class StoreAccountUpdateForm extends StoreAccountCreationForm
{
    protected function buildForm()
    {
        parent::buildForm();
        $this->formBuilder
            ->add("id", "text", array(
                "constraints" => array(
                    new NotBlank()
                )
            ))
            ->remove('password')
            ->remove('password_confirm')
        ;

    }

    public function getName()
    {
        return "store_account_update";
    }
}