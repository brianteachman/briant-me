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
                // $this->flashMessenger()->addMessage(serialize($validData));
                // $this->sendContactForm($validData);

                $this->redirect()->toUrl('home');
            }
        }

        return new ViewModel(array('form' => $form));
    }
}
