<?php
namespace Application\Form;

use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;

class ContactForm extends Form
{
    protected $captcha;

    public function setCaptcha(CaptchaAdapter $captcha)
    {
        $this->captcha = $captcha;
    }

    // public function __construct(CaptchaAdapter $captcha)
    public function prepareElements()
    {
        // add() can take either an Element/Fieldset instance,
        // or a specification, from which the appropriate object
        // will be built.

        $this->add(array(
            'type'  => 'Text',
            'name' => 'name',
            // 'options' => array(
            //     'label' => 'Your name',
            // ),
            'attributes' => array(
                'tabindex' => '1',
                'size' => '18',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Email',
            'name' => 'email',
            // 'options' => array(
            //     'label' => 'Your email address',
            // ),
            'attributes' => array(
                'tabindex' => '2',
                'size' => '25',
                'class' => 'input-xlarge',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'message',
            // 'options' => array(
            //     'label' => 'Message',
            // ),
            'attributes' => array(
                'tabindex' => '3',
                'class' => 'input-xlarge',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Captcha',
            'name' => 'captcha',
            'options' => array(
                'label' => 'Not a bot... prove it.',
                'captcha' => $this->captcha,
            ),
            'attributes' => array(
                'placeholder' => 'Enter the characters above',
            ),
        ));
        $this->add(new Element\Csrf('security'));
        $this->add(array(
            'name' => 'send',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Submit',
                'class' => 'btn btn-succes btn-large',
            ),
        ));

        // We could also define the input filter here, or
        // lazy-create it in the getInputFilter() method.
    }
}
