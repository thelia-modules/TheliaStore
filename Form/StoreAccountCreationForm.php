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

class StoreAccountCreationForm extends BaseForm
{
    use AddressCountryValidationTrait;

    protected function buildForm()
    {
        $this->formBuilder
            ->add("email", "email", array(
                "constraints" => array(
                    new NotBlank(),
                    new Email(),
                    new Callback(array(
                        "methods" => array(
                            array($this, "verifyExistingEmail"),
                        ),
                    )),
                ),
                "label" => $this->translator->trans("Email address"),
                "label_attr" => array(
                    "for" => "email",
                    'help' => $this->translator->trans("Please enter a valid email address")
                ),
                'attr'        => [
                    'placeholder' => $this->translator->trans('Administrator email address'),
                ]
            ))
            ->add("password", "password", array(
                "constraints" => array(),
                "label" => $this->translator->trans("Password"),
                "label_attr" => array(
                    "for" => "password",
                ),
            ))
            ->add("password_confirm", "password", array(
                "constraints" => array(
                    new Callback(array("methods" => array(
                        array($this, "verifyPasswordField"),
                    ))),
                ),
                "label" => $this->translator->trans('Password confirmation'),
                "label_attr" => array(
                    "for" => "password_confirmation",
                ),
            ))
            ->add("firstname", "text", array(
                "constraints" => array(
                    new NotBlank(),
                ),
                "label" => Translator::getInstance()->trans("First Name"),
                "label_attr" => array(
                    "for" => "firstname",
                ),
            ))
            ->add("lastname", "text", array(
                "constraints" => array(
                    new NotBlank(),
                ),
                "label" => Translator::getInstance()->trans("Last Name"),
                "label_attr" => array(
                    "for" => "lastname",
                ),
            ))
            ->add("address1", "text", array(
                "constraints" => array(
                    new NotBlank(),
                ),
                "label_attr" => array(
                    "for" => "address",
                ),
                "label" => Translator::getInstance()->trans("Street Address "),
            ))
            ->add("zipcode", "text", array(
                "constraints" => array(
                    new NotBlank(),
                    new Callback(array(
                        "methods" => array(
                            array($this, "verifyZipCode")
                        ),
                    )),
                ),
                "label" => Translator::getInstance()->trans("Zip code"),
                "label_attr" => array(
                    "for" => "zipcode",
                ),
            ))
            ->add("city", "text", array(
                "constraints" => array(
                    new NotBlank(),
                ),
                "label" => Translator::getInstance()->trans("City"),
                "label_attr" => array(
                    "for" => "city",
                ),
            ))
            ->add("country", "text", array(
                "constraints" => array(
                    new NotBlank(),
                ),
                "label" => Translator::getInstance()->trans("Country"),
                "label_attr" => array(
                    "for" => "country",
                ),
            ))
            ->add("title", "text", array(
                "constraints" => array(
                    new NotBlank(),
                ),
                "label" => Translator::getInstance()->trans("Title"),
                "label_attr" => array(
                    "for" => "title",
                ),
            ))
            ->add("lang", "text", array(
                "constraints" => array(
                    new NotBlank(),
                ),
                "label" => Translator::getInstance()->trans("Lang"),
                "label_attr" => array(
                    "for" => "title",
                ),
            ))

        ;
    }
    public function verifyPasswordField($value, ExecutionContextInterface $context)
    {
        $data = $context->getRoot()->getData();

        if ($data["password"] === '' && $data["password_confirm"] === '') {
            $context->addViolation("password can't be empty");
        }

        if ($data["password"] != $data["password_confirm"]) {
            $context->addViolation("password confirmation is not the same as password field");
        }
        /*
        $minLength = ConfigQuery::getMinimuAdminPasswordLength();

        if (strlen($data["password"]) < $minLength) {
            $context->addViolation("password must be composed of at least $minLength characters");
        }
        */
    }

    public function verifyExistingEmail($value, ExecutionContextInterface $context)
    {
        /*
        if (null !== $administrator = AdminQuery::create()->findOneByEmail($value)) {
            $context->addViolation($this->translator->trans("An administrator with thie email address already exists"));
        }
        */
    }
    public function getName()
    {
        return "store_account_creation";
    }
}