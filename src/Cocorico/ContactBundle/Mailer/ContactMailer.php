<?php


namespace Cocorico\ContactBundle\Mailer;


use Cocorico\ContactBundle\Entity\Contact;

class ContactMailer implements MailerInterface
{
    protected $mailer;
    protected $twig;
    protected $parameters;
    protected $templates;
    protected $fromEmail;
    protected $contactEmail;
    protected $locale;
    protected $sendgridKey;

    public function __construct(\Twig_Environment $twig, array $parameters, array $templates, string $sendgridKey)
    {
        $this->twig         = $twig;
        $this->parameters   = $parameters['parameters'];
        $this->fromEmail    = $parameters['parameters']['cocorico_contact_from_email'];
        $this->contactEmail = $parameters['parameters']['cocorico_contact_contact_email'];
        $this->templates    = $templates;
        $this->locale       = $parameters['parameters']['cocorico_locale'];
        $this->sendgridKey  = $sendgridKey;
    }

    /**
     * email is sent to admin
     *
     * @param Contact $contact
     *
     * @return void
     */
    public function sendContactMessage(Contact $contact)
    {
        $template = $this->templates['templates']['contact_message'];

        $context = [
            'contact' => $contact,
            'user_locale' => $this->locale,
        ];

        $this->sendMessage($template, $context, $this->fromEmail, $this->contactEmail);
    }


    /**
     * @param string $templateName
     * @param array  $context
     * @param string $fromEmail
     * @param string $toEmail
     */
    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
//        $toEmail = "imanalopher@gmail.com";
        $context['user_locale'] = $this->locale;
        $context['locale'] = $this->locale;
        $context['app']['request']['locale'] = $this->locale;

        try {
            /** @var \Twig_Template $template */
            $template = $this->twig->loadTemplate($templateName);
            $context = $this->twig->mergeGlobals($context);

            $subject = $template->renderBlock('subject', $context);
            $context["message"] = $template->renderBlock('message', $context);

            $textBody = $template->renderBlock('body_text', $context);
            $htmlBody = $template->renderBlock('body_html', $context);

            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($fromEmail, "Otomo");
            $email->setSubject($subject);
            $email->addTo($toEmail, "Otomo");
            $email->addContent("text/plain", $textBody);
            $email->addContent("text/html", $htmlBody);
            $sendgrid = new \SendGrid($this->sendgridKey);

            $sendgrid->send($email);
        } catch (\Exception $e) {
        }

    }
}