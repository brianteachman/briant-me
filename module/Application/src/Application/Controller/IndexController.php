<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Captcha\Figlet as Captcha;
use Zend\Mail;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
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
           'to_name' => 'BrianT',
           'subject' => 'www.briant.me Contact Form',
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

    /**
     * Multi-part sendmail method
     *
     * $head = array(
     *     'from' => '',
     *     'from_name' => '',
     *     'to' => '',
     *     'to_name' => '',
     *     'subject' => '',
     * )
     * 
     * @param string[] $head      Array containing mailto info.
     * @param string   $html_body Plain text email message.
     * @param string   $text_body HTML email message.
     */
    protected function sendMultiPartMail($meta, $html_body="", $text_body="")
    {
        if (!isset($meta['to']) || !isset($meta['from'])) {
            throw new \InvalidArguementException("'to' and 'from' must be set.");
        }

        $mail = new Mail\Message();
        $mail->setEncoding("UTF-8");
        
        $mail->setFrom($meta['from'], $meta['from_name'])
             ->addTo($meta['to'], $meta['to_name'])
             ->setSubject($meta['subject']);

        if (isset($meta['reply_to'])) {
            $mail->addReplyTo($meta['reply_to'], $meta['reply_to_name']);
        }

        $text = new MimePart($text_body);
        $text->type = "text/plain";

        $html = new MimePart($html_body);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($text, $html));

        $mail->setBody($body);
        $mail->getHeaders()->get('content-type')->setType('multipart/alternative');

        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);
    }
}
