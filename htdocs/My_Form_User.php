<?php
require_once('My_Validator_Email.php');
require_once('Zend/Form.php');
require_once('Zend/Form/Element/MultiCheckbox.php');

class My_Form_User extends Zend_Form
{
    public function init() {
        // username
        $this->addElement('text','username', array(
            'required' => true,
            'validators' => array(
                // type, breakchain, validator constructor options
                array('Alnum', false, false),
                array('StringLength', false, array(6, 16)),
            ),
        ));

        // email
        $EmailValidate = new My_Validator_Email();
        $this->addElement('text','email', array(
            'required' => true,
            'validators' => array(
//                array('NotEmpty', false),
                array('EmailAddress', false),
                array($EmailValidate, false)
            )
        ));

        // add checkboxes with user groups
        $elmGroup = new Zend_Form_Element_MultiCheckbox('group');
        $elmGroup->setMultiOptions(array(
            'guest'=>'guest',
            'contributor'=>'contributor',
            'editor'=>'editor',
            'administrator'=>'administrator'
        ));
        $elmGroup->setRequired(true);
        $this->addElement($elmGroup);
    }
}