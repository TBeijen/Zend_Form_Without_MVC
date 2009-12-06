<?php
require_once('Zend/Form/Decorator/Abstract.php');

/**
 * Create Decorator by extending Zend_Form_Decorator_Abstract or
 * implementing Zend_Form_Decorator_Interface
 */
class My_Decorator_Errors extends Zend_Form_Decorator_Abstract
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

        // get element and errors
        $element = $this->getElement();
        $errorMessages = $element->getMessages();
        if (empty($errorMessages)) {
            return $content;
        }

        // iterate over errorMessages, replace or use default
        $errorLinesHtml = '';
        foreach ($errorMessages as $errorCode => $errorMsg) {
            // make an exception for isEmpty and array representing elements
            if ($element->isArray() && $errorCode=='isEmpty') {
                $msgLangPack = $this->lang->get('form.error.array.' . $errorCode);
            } else {
                $msgLangPack = $this->lang->get('form.error.' . $errorCode);
            }
            $errorMsg = ($msgLangPack !== false) ? $msgLangPack : $errorMsg;
            $errorLinesHtml .= '<li>' . $errorMsg . '</li>';
        }
        // combine
        $errorHtml = '<ul class="errors">' . $errorLinesHtml .'</ul>';

        // get separator and placement and return decorated $content
        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        switch ($placement) {
            case self::APPEND:
                return $content . $separator . $errorHtml;
            case self::PREPEND:
                return $errorHtml . $separator . $content;
        }
    }
}