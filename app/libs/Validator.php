<?php

namespace QInterface\Libs;

use Log;
use Illuminate\Validation\Validator as BaseValidator;
use Symfony\Component\Translation\TranslatorInterface;

class Validator extends BaseValidator
{

    public function __construct(TranslatorInterface $translator, $data, $rules, $messages = array())
    {
        parent::__construct($translator, $data, $rules, $messages);
    }

    /**
     * Validate a given attribute against a rule.
     *
     * @param  string  $attribute
     * @param  string  $rule
     * @return void
     */
    protected function validate($attribute, $rule)
    {
        list($rule, $parameters) = $this->parseRule($rule);

        // We will get the value for the given attribute from the array of data and then
        // verify that the attribute is indeed validatable. Unless the rule implies
        // that the attribute is required, rules are not run for missing values.
        $value = $this->getValue($attribute);

        $validatable = $this->isValidatable($rule, $attribute, $value);

        $method = "validate{$rule}";

        if ($validatable and ! $this->$method($attribute, $value, $parameters, $this))
        {
            $this->addFailure($attribute, $rule, $parameters);
        }
    }

    protected function validateElementBetween($attribute, $value, $parameters)
    {

        foreach ($value as $element) {
            if ( ! $this->validateBetween($attribute, $element, $parameters)) {
                return false;
            }
        }

        return true;
    }

    protected function replaceElementBetween($message, $attribute, $rule, $parameters)
    {
        return $this->replaceBetween($message, $attribute, $rule, $parameters);
    }

}