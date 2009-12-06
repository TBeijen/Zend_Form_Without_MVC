<?php
require_once('My_LanguagePack.php');
require_once('Zend/View.php');
require_once('My_Decorator_Errors.php');
require_once('Zend/Form/Decorator/HtmlTag.php');
require_once('Zend/Form/Decorator/Description.php');

class My_Form_Renderer_Edit
{
    /**
     * Form instance
     * @var Zend_Form
     */
    protected $form;

    /**
     * Language pack (demonstration purposes only, could be antything)
     * @var My_LanguagePack
     */
    protected $lang;

    /**
     * Constructor requiring Zend_Form instance to be rendered
     * @param Zend_Form $form
     */
    public function __construct(Zend_Form $form, $form_id = null)
    {
        // create view and specify doctype to have self-closing tags.
        $view = new Zend_View();
        $view->doctype('XHTML1_TRANSITIONAL');

        $this->form = $form;
        $this->form->setView(new Zend_View());
        $this->form->setAttrib('class', 'form_edit');
        if (!is_null($form_id)) {
            $this->form->setAttrib('id', $form_id);
        }

        // Zend_Translate could be an option but the scope is a situation
        // where allready other internationalisation systems exist
        $this->lang = new My_LanguagePack();
    }

    /**
     * Setup decorators and properties and return render output
     * 
     * @return string
     */
    public function render()
    {
        $this->addElements();
        $this->setupElementsCommon();
        $this->setupElementsUnique();
        $this->setupForm();
        return $this->form->render();
    }

    /**
     * Add submit button
     */
    protected function addElements() {
        $this->form->addElement('submit','submit', array(
            'label'=>'Send',
        ));
    }

    /**
     * Setup decorators and properties common for all elements
     */
    protected function setupElementsCommon()
    {
         $this->form->setElementDecorators(array(
            'ViewHelper',
            array('Description', array(
                'placement' => 'append',
                'tag' => 'span',
                'class' => 'description'
            )),
         ));
    }

    /**
     * Iterate over elements and hand over to
     */
    protected function setupElementsUnique()
    {
        foreach($this->form->getElements() as $elmKey => $elm) {
            $this->setupSingleElement($elm);
        }
    }

    /**
     * Setup per-element properties like labels, and classes
     */
    protected function setupSingleElement(Zend_Form_Element $elm) {
        // determine if this element has an error. (Will be used below)
        $elmHasError = (count($elm->getMessages()) > 0);

        // set element values from the language pack
        $elm->setLabel($this->lang->get('form.label.' . $elm->getName()));
        // display info about required length if validator exists
        if ($elm->getValidator('StringLength')) {
            $elm->setDescription(sprintf($this->lang->get('form.description.' . $elm->getName()),
                $elm->getValidator('StringLength')->getMin(),
                $elm->getValidator('StringLength')->getMax()
            ));
        } else {
            $elm->setDescription($this->lang->get('form.description.' . $elm->getName()));
        }

        // Duplicating type attr to classname in case we need to support IE6
        // and want to be able to directly target the element without using
        // input[type=text]
        $zendType = $elm->getType();
        $className = strtolower(
            substr($zendType, strrpos($zendType, '_') + 1)
        );
        $elm->setAttrib('class', $className);

        // wrap this stuff up in a html div with class 'element'
        $elm->addDecorator('HtmlTag',array('tag'=>'div', 'class'=>'element'));

        // determine if element has error and use that to determine prefix char.
        // 1. There seems to be no way to add html to the reqPrefix
        // 2. There seems to be no way to add a custom classname to the div tag
        if ($elm->getName() != 'submit' ) {
            $reqChar = $elmHasError ? '! ' : '* ';
            $elm->addDecorator('Label', array(
                'placement' => 'prepend',
                'tag' => 'div',
                'requiredPrefix' => $reqChar
            ));
        }

        // use custom error decorator that attempts to replace default error
        // messages by the ones supplied by My_LanguagaPack
        $errorDecorator = new My_Decorator_Errors();
        $errorDecorator->setLanguagePack($this->lang);
        $elm->addDecorator($errorDecorator);
        
        // wrap everything so far in a li tag, give it class error if elm has error
        // ATT: using array to create alias for allready used HtmlTag decorator
        $liClass = $elmHasError ? 'error' : '';
        $elm->addDecorator(
            array('outerLi' => 'HtmlTag'),
            array('tag' => 'li', 'class' => $liClass)
        );
    }

    /**
     * Set form decorators. basically replacing the default <dl> with a <ul>
     */
    protected function setupForm()
    {
        // remove default decorators and add own
        $this->form->clearDecorators();
        $this->form->addDecorator('FormElements');
        $this->form->addDecorator('HtmlTag', array('tag' => 'ul'));
        $this->form->addDecorator('Form');
    }
}