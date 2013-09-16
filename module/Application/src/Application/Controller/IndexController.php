<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Captcha\Figlet as Captcha;
use Application\Form\ContactForm;


class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function aboutAction()
    {
        return new ViewModel();
    }

    public function servicesAction()
    {
        return new ViewModel();
    }

    public function pricingAction()
    {
        return new ViewModel();
    }

    public function contactAction()
    {
        // configure captcha and in inject into ContactForm
        $captcha = new Captcha(array(
            'name' => 'Mangle',
            'wordLen' => 3,
            'timeout' => 300,
        ));
        $form = new ContactForm();
        $form->setCaptcha($captcha);
        $form->prepareElements();

        $request = $this->getRequest();
        if ($request->isPost()) {
            // $info = new MessageInfo();
            // $form->setInputFilter($info->getInputFilter());

            $form->setData($request->getPost());
            if ($form->isValid()) {

                $validData = $form->getData();
                $this->flashMessenger()->addMessage(serialize($validData));
                $this->sendContactForm($validData);

                $this->redirect()->toUrl('home');
            }
        }

        return new ViewModel(array('form' => $form));
    }

    /**
     * Multi-part sendmail method
     * 
     * @param  string[] $info Array containing elements of the message.
     */
    protected function sendContactForm($info)
    {
        $meta = array(
           'from' => $info['email'],
           'from_name' => $info['name'],
           'to' => 'me@briant.me',
           'to_name' => 'Energy Specialist',
           'subject' => 'Sacred-Energy Contact Form',
        );

        $text_body = <<<TEXTBODY
{$info['name']}: \n{$info['message']}.
TEXTBODY;

        $html_body = <<<HTMLBODY
<html><head></head><body>
<h1>{$info['name']} say's:</h1>
<p>{$info['message']}</p>
</body></html>
HTMLBODY;

        try {
            // defined in TWeb\Controller\AbstractController
            $this->sendMultiPartMail($meta, $html_body, $text_body);
        } catch (Exception $e) {
            //log it or something
        }
    }
}
