<?php
require_once('Zend/Validate/Abstract.php');

/**
 * Create Validator by extending Zend_Validate_Abstract or
 * implementing Zend_Validate_Interface
 */
class My_Validator_Email extends Zend_Validate_Abstract
{
    protected $isValid = null;

    public function isValid($value) {
        $this->isValid = !in_array($value, array(
            'duplicate@test.com',
        ));
        return $this->isValid;
    }

    public function getMessages() {
        if ($this->isValid === false) {
            return array(
                'duplicateEmail' => 'This email address is allready used'
            );
        }
        return array();
    }

    public function getErrors() {
        return array_keys($this->getMessages());
    }
}