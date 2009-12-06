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



        // add checkboxes with user groups.
        // Perform translation here. (could as well have been titles from a DB
        // so it's not something the renderer should do, as opposed to labels
        // and error messages)
        $lang = new My_LanguagePack();

        $groupIds = array(1,2,3,4);
        $groupOptions = array();
        foreach ($groupIds as $id) {
            $groupOptions[$id] = $lang->get('group.label.' . $id);
        }

        $elmGroup = new Zend_Form_Element_MultiCheckbox('group');
        $elmGroup->setMultiOptions( $groupOptions );
        $elmGroup->setRequired(true);
        $this->addElement($elmGroup);
    }
}