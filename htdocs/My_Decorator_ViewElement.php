<?php
require_once('Zend/Form/Decorator/Abstract.php');

/**
 * Create Decorator by extending Zend_Form_Decorator_Abstract or
 * implementing Zend_Form_Decorator_Interface
 */
class My_Decorator_ViewElement extends Zend_Form_Decorator_Abstract
{
    protected $lang = null;

    public function setLanguagePack(My_LanguagePack $lang)
    {
        $this->lang = $lang;
    }

    public function render($content)
    {
        // if not set yet try to get languagePack from options
        if (is_null($this->lang)) {
            $this->lang = $this->getOption('lang');
        }
        // throw exception if lang doesn't exist
        if (!$this->lang instanceof My_LanguagePack) {
            require_once 'Zend/Form/Decorator/Exception.php';
            throw new Zend_Form_Decorator_Exception(
                __METHOD__.', cannot operate without langugagePack'
            );
        }

        // get element
        $elm = $this->getElement();

        $label = $this->lang->get('form.label.' . $elm->getName());
        $value = htmlentities( $elm->getValue());

        $elementHtml = '<dt>' . $label . '</dt><dd>' . $value . '</dd>';

        // get separator and placement and return decorated $content
        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        switch ($placement) {
            case self::APPEND:
                return $content . $separator . $elementHtml;
            case self::PREPEND:
                return $elementHtml . $separator . $content;
        }
    }
}