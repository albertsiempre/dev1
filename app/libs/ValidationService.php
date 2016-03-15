<?php

namespace QInterface\Libs;

use Input;
use Closure;
use QInterface\Libs\Validator;
use Validator as ValidatorFactory;

abstract class ValidationService
{
    /**
     * Validator instance.
     *
     * @access protected
     * @var    Validator
     */
    protected $validator;

    /**
     * Error messages.
     *
     * @access protected
     * @var    array
     */
    protected $errors;

    /**
     * Input to validate.
     *
     * @access protected
     * @var    array
     */
    protected $input;

    /**
     * Validation rules.
     *
     * @access protected
     * @var    array
     */
    protected static $rules = array();

    /**
     * Custom error messages
     *
     * @access protected
     * @var    array
     */
    protected static $messages = array();

    protected static $registered = false;

    /**
     * Assign the input as a property.
     *
     * @access public
     * @param  array  $input
     */
    public function __construct($input = null)
    {
        $this->input = $input ?: Input::all();

        if ( ! static::$registered) {
            ValidatorFactory::resolver(function ($translator, $data, $rules, $messages)
            {
                return new Validator($translator, $data, $rules, $messages);
            });

            static::$registered = true;
        }
    }

    /**
     * Check whether the input passes the validation test.
     *
     * @access public
     * @return bool
     */
    public function passes()
    {
        $this->validator = ValidatorFactory::make($this->input, static::$rules, static::$messages);

        if ($this->validator->passes()) {
            return true;
        }

        $this->errors = $this->validator->messages();

        return false;
    }

    /**
     * Return the error messages
     *
     * @access public
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Extend the Validation validation rule
     *
     * @access public
     * @param  string         $rule
     * @param  mixed          $extension
     * @return ValidationService
     */
    public function extend($rule, $extension)
    {
        ValidatorFactory::extend($rule, $extension);

        return $this;
    }

    public function extendImplicit($rule, Closure $extensions)
    {
        ValidatorFactory::extendImplicit($rule, $extensions);

        return $this;
    }

}
