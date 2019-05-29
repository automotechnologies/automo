<?php

namespace Cocorico\UserBundle\Mailer;

use Cocorico\UserBundle\Entity\User;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailNotification
{
    protected $locale;
    protected $locales;
    protected $mailer;
    protected $router;
    protected $twig;
    protected $requestStack;
    protected $parameters;
    protected $fromEmail;

    /**
     * @param \Swift_Mailer         $mailer
     * @param UrlGeneratorInterface $router
     * @param \Twig_Environment     $twig
     * @param RequestStack          $requestStack
     * @param array                 $parameters
     */
    public function __construct(
        \Swift_Mailer $mailer,
        UrlGeneratorInterface $router,
        \Twig_Environment $twig,
        RequestStack $requestStack,
        array $parameters
    ) {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->parameters = $parameters;

        $this->locales = $parameters['locales'];
        $this->fromEmail = $parameters['from_email'];
        $this->locale = $parameters['locale'];
        if ($requestStack->getCurrentRequest()) {
            $this->locale = $requestStack->getCurrentRequest()->getLocale();
        }
    }

    /**
     * @param UserInterface $user
     */
    public function sendAccountCreationConfirmationMessageToUser(UserInterface $user)
    {
        $template = $this->parameters['templates']['account_creation_confirmation_user'];
        $url = $this->router->generate(
            'cocorico_user_register_confirmation',
            ['token' => $user->getConfirmationToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $context = [
            'user' => $user,
            'confirmationUrl' => $url
        ];

        $this->sendMessage($template, $context, $this->fromEmail, $user->getEmail());
    }

    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
        $toEmail = "imanalopher@gmail.com";
        $context['user_locale'] = $this->locale;
        $context['locale'] = $this->locale;
        $context['app']['request']['locale'] = $this->locale;

        if (isset($context['user'])) {
            /** @var User $user */
            $user = $context['user'];
            $context['user_locale'] = $user->guessPreferredLanguage($this->locales, $this->locale);
            $context['locale'] = $context['user_locale'];
            $context['app']['request']['locale'] = $context['user_locale'];
        }

        try {
            /** @var \Twig_Template $template */
            $template = $this->twig->loadTemplate($templateName);
            $context = $this->twig->mergeGlobals($context);

            $subject = $template->renderBlock('subject', $context);
            $context["message"] = $template->renderBlock('message', $context);

            $textBody = $template->renderBlock('body_text', $context);
            $htmlBody = $template->renderBlock('body_html', $context);

//            $message = (new \Swift_Message($subject))
//                ->setFrom($fromEmail)
//                ->setTo($toEmail);
//            if (!empty($htmlBody)) {
//                $message
//                    ->setBody($htmlBody, 'text/html')
//                    ->addPart($textBody, 'text/plain');
//            } else {
//                $message->setBody($textBody);
//            }
//            $this->mailer->send($message);


            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($fromEmail, "Otomo");
            $email->setSubject($subject);
            $email->addTo($toEmail, "Otomo");
            $email->addContent("text/plain", $textBody);
            $email->addContent("text/html", $htmlBody);
            $sendgrid = new \SendGrid('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');
            try {
                $response = $sendgrid->send($email);
                dump($response->statusCode());
                dump($response->headers());
                dump($response->body());
            } catch (\Exception $e) {
                dump('Caught exception: '. $e->getMessage());
            }
            dump($email);
            die;
        } catch (\Exception $e) {
        }
    }

    public function send(User $user)
    {
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("", "Otomo");
        $email->setSubject("Sending with Twilio SendGrid is Fun");
        $email->addTo($user->getEmail(), "Otomo");
        $email->addContent("text/plain", "and easy to do anywhere, even with PHP");

        $email->addContent("text/html", 'CocoricoUserBundle:Mails/User:account_creation_confirmation_user.txt.twig');
        $sendgrid = new \SendGrid('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');
        try {
            $response = $sendgrid->send($email);
            dump($response->statusCode());
            dump($response->headers());
            dump($response->body());
        } catch (\Exception $e) {
            dump('Caught exception: '. $e->getMessage());
        }
        dump($email);
        die;
    }
}