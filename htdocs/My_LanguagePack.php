<?php
class My_LanguagePack
{
    protected $langDef = array();

    public function __construct()
    {
        $this->langDef = array(
            'form.label.username' => 'Username',
            'form.label.email' => 'Email',
            'form.label.group' => 'Groups',
            'form.label.submit' => 'Save',

            'form.description.username' => '(Min %s, max %s char.)',
            'form.description.group' => 'Select at least one',

            'form.error.duplicateEmail' => 'This email address is allready used by another user',
            'form.error.emailAddressInvalidFormat' => 'Please enter a valid email address',
            'form.error.isEmpty' => 'Please enter a value',
            'form.error.array.isEmpty' => 'At least one option needs to be selected',

            'group.label.1' => 'Guests',
            'group.label.2' => 'Contributors',
            'group.label.3' => 'Editors',
            'group.label.4' => 'Administrators',
        );
    }

    public function get($tag='') {
        if (isset($this->langDef[$tag])) {
            return $this->langDef[$tag];
        }
        return false;
    }
}