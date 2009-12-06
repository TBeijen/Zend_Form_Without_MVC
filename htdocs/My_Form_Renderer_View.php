<?php
require_once('My_Decorator_ViewElement.php');

class My_Form_Renderer_View
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
        $this->form = $form;
        $this->form->setAttrib('class', 'form_view');
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
        $this->setupElementsCommon();
        $this->setupForm();
        return $this->form->render();
    }

    /**
     * Setup decorators and properties common for all elements
     */
    protected function setupElementsCommon()
    {
        $viewElementDecorator = new My_Decorator_ViewElement();
        $viewElementDecorator->setLanguagePack($this->lang);

        $this->form->clearDecorators();
        $this->form->setElementDecorators(array($viewElementDecorator));
    }

    /**
     * Set form decorators.
     */
    protected function setupForm()
    {
        // remove default decorators and add own
        $this->form->clearDecorators();
        $this->form->addDecorator('FormElements');
        $this->form->addDecorator('HtmlTag', array(
            'tag' => 'dl',
            'id' => $this->form->getId(),
            'class' => 'form_view',
        ));
    }
}