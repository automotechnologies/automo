<?php


namespace Cocorico\CoreBundle\Mailer;


use Cocorico\CoreBundle\Entity\Booking;
use Cocorico\CoreBundle\Entity\Listing;
use Cocorico\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\Translator;

class SendgridMailerValidateBookings implements MailerInterface
{
    const TRANS_DOMAIN = 'cocorico_mail';

    protected $mailer;
    protected $router;
    protected $twig;
    protected $requestStack;
    protected $translator;
    protected $timeUnit;
    protected $timeUnitIsDay;
    protected $locale;
    /** @var  array locales */
    protected $locales;
    protected $timezone;
    protected $templates;
    protected $fromEmail;
    protected $adminEmail;
    protected $sendgridKey;

    /**
     * @param \Swift_Mailer         $mailer
     * @param UrlGeneratorInterface $router
     * @param \Twig_Environment     $twig
     * @param RequestStack          $requestStack
     * @param Translator            $translator
     * @param array                 $parameters
     * @param array                 $templates
     */
    public function __construct(
        \Swift_Mailer $mailer,
        UrlGeneratorInterface $router,
        \Twig_Environment $twig,
        RequestStack $requestStack,
        Translator $translator,
        string $sendgridKey,
        array $parameters,
        array $templates
    ) {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->translator = $translator;
        $this->sendgridKey = $sendgridKey;

        /** parameters */
        $parameters = $parameters['parameters'];

        $this->fromEmail = $parameters['cocorico_from_email'];
        $this->adminEmail = $parameters['cocorico_contact_email'];

        $this->timeUnit = $parameters['cocorico_time_unit'];
        $this->timeUnitIsDay = ($this->timeUnit % 1440 == 0) ? true : false;

        $this->locales = $parameters['cocorico_locales'];
        $this->locale = $parameters['cocorico_locale'];
        $this->timezone = $parameters['cocorico_time_zone'];

        if ($requestStack->getCurrentRequest()) {
            $this->locale = $requestStack->getCurrentRequest()->getLocale();
        }

        $this->templates = $templates['templates'];
    }

    /**
     * email is sent when a listing is activated
     *
     * @param Listing $listing
     *
     * @return void
     */
    public function sendListingActivatedMessageToOfferer(Listing $listing)
    {
        // TODO: Implement sendListingActivatedMessageToOfferer() method.
    }

    /**
     * Send an email to offerer to confirm the new booking request
     *
     * @param Booking $booking
     *
     * @return void
     */
    public function sendBookingRequestMessageToOfferer(Booking $booking)
    {
        // TODO: Implement sendBookingRequestMessageToOfferer() method.
    }

    /**
     * Send an email to offerer to inform the booking acceptation
     *
     * @param Booking $booking
     *
     * @return void
     */
    public function sendBookingAcceptedMessageToOfferer(Booking $booking)
    {
        // TODO: Implement sendBookingAcceptedMessageToOfferer() method.
    }

    /**
     * email is sent when the offerer rejects a reservation request
     *
     * @param Booking $booking
     *
     * @return void
     */
    public function sendBookingRefusedMessageToOfferer(Booking $booking)
    {
        // TODO: Implement sendBookingRefusedMessageToOfferer() method.
    }

    /**
     * email is sent 2 hours before a reservation request expires.
     *
     * @param Booking $booking
     * @return void
     */
    public function sendBookingExpirationAlertMessageToOfferer(Booking $booking)
    {
        // TODO: Implement sendBookingExpirationAlertMessageToOfferer() method.
    }

    /**
     * Send an email to offerer to inform the new booking expiration
     *
     * @param Booking $booking
     *
     * @return void
     */
    public function sendBookingRequestExpiredMessageToOfferer(Booking $booking)
    {
        $listing = $booking->getListing();

        $user = $listing->getUser();
        $userLocale = $user->guessPreferredLanguage($this->locales, $this->locale);
        $asker = $booking->getUser();
        $template = $this->templates['booking_request_expired_offerer'];

        $bookingRequestUrl = $this->router->generate(
            'cocorico_dashboard_booking_show_offerer',
            [
                'id' => $booking->getId(),
                '_locale' => $userLocale,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $context = [
            'user' => $user,
            'asker' => $asker,
            'listing' => $listing,
            'booking' => $booking,
            'booking_request_url' => $bookingRequestUrl,
        ];

        $this->sendMessage($template, $context, $this->fromEmail, $user->getEmail());
    }

    /**
     * email is sent 24 hours after the end of the reservation.
     * so, that the offerer can rate the asker.
     *
     * @param Booking $booking
     * @return void
     */
    public function sendReminderToRateAskerMessageToOfferer(Booking $booking)
    {
        $listing = $booking->getListing();
        $user = $listing->getUser();
        $userLocale = $user->guessPreferredLanguage($this->locales, $this->locale);
        $asker = $booking->getUser();
        $template = $this->templates['reminder_to_rate_asker_offerer'];

        $offererToAskerReviewUrl = $this->router->generate(
            'cocorico_dashboard_review_new',
            [
                'booking_id' => $booking->getId(),
                '_locale' => $userLocale
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $context = [
            'user' => $user,
            'asker' => $asker,
            'booking' => $booking,
            'offerer_to_asker_review_url' => $offererToAskerReviewUrl
        ];

        $this->sendMessage($template, $context, $this->fromEmail, $user->getEmail());
    }

    /**
     * email is sent if the asker cancels his reservation
     *
     * @param Booking $booking
     *
     * @return void
     */
    public function sendBookingCanceledByAskerMessageToOfferer(Booking $booking)
    {
        // TODO: Implement sendBookingCanceledByAskerMessageToOfferer() method.
    }

    /**
     * email is sent 24 hours before the booking begins
     *
     * @param Booking $booking
     *
     * @return void
     */
    public function sendBookingImminentMessageToOfferer(Booking $booking)
    {
        $listing = $booking->getListing();
        $user = $listing->getUser();
        $userLocale = $user->guessPreferredLanguage($this->locales, $this->locale);
        $asker = $booking->getUser();
        $template = $this->templates['booking_imminent_offerer'];

        $bookingRequestUrl = $this->router->generate(
            'cocorico_dashboard_booking_show_offerer',
            [
                'id' => $booking->getId(),
                '_locale' => $userLocale,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $context = [
            'user' => $user,
            'asker' => $asker,
            'listing' => $listing,
            'booking' => $booking,
            'booking_request_url' => $bookingRequestUrl,
        ];

        $this->sendMessage($template, $context, $this->fromEmail, $user->getEmail());
    }

    /**
     * email is sent when money is wired to the offerer
     *
     * @param Booking $booking
     *
     * @return void
     */
    public function sendWireTransferMessageToOfferer(Booking $booking)
    {
        // TODO: Implement sendWireTransferMessageToOfferer() method.
    }

    /**
     * This is a reminder email that is sent every 27th day of the month.
     * It is sent only to users that have an active listing.
     *
     * @param Listing $listing
     *
     * @return void
     */
    public function sendUpdateYourCalendarMessageToOfferer(Listing $listing)
    {
        // TODO: Implement sendUpdateYourCalendarMessageToOfferer() method.
    }

    /**
     * Send an email to asker to confirm the new booking request
     *
     * @param Booking $booking
     *
     * @return void
     */
    public function sendBookingRequestMessageToAsker(Booking $booking)
    {
        // TODO: Implement sendBookingRequestMessageToAsker() method.
    }

    /**
     * Send an email to asker to inform the booking acceptation
     *
     * @param Booking $booking
     *
     * @return void
     */
    public function sendBookingAcceptedMessageToAsker(Booking $booking)
    {
        // TODO: Implement sendBookingAcceptedMessageToAsker() method.
    }

    /**
     * Send an email to asker to inform the booking refusal
     *
     * @param Booking $booking
     *
     * @return void
     */
    public function sendBookingRefusedMessageToAsker(Booking $booking)
    {
        // TODO: Implement sendBookingRefusedMessageToAsker() method.
    }

    /**
     * Send an email to asker to inform the new booking expiration
     *
     * @param Booking $booking
     *
     * @return void
     */
    public function sendBookingRequestExpiredMessageToAsker(Booking $booking)
    {
        $user = $booking->getUser();
        $template = $this->templates['booking_request_expired_asker'];
        $userLocale = $user->guessPreferredLanguage($this->locales, $this->locale);

        $similarListingUrl = $this->router->generate(
            'cocorico_home',
            [
                '_locale' => $userLocale,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $context = [
            'user' => $user,
            'booking' => $booking,
            'similar_booking_listings_url' => $similarListingUrl
        ];

        $this->sendMessage($template, $context, $this->fromEmail, $user->getEmail());
    }

    /**
     * email is sent when the offerer rejects a reservation request
     *
     * @param Booking $booking
     *
     * @return void
     */
    public function sendBookingImminentMessageToAsker(Booking $booking)
    {
        $user = $booking->getUser();
        $userLocale = $user->guessPreferredLanguage($this->locales, $this->locale);
        $listing = $booking->getListing();
        $offerer = $listing->getUser();
        $template = $this->templates['booking_imminent_asker'];

        $bookingRequestUrl = $this->router->generate(
            'cocorico_dashboard_booking_show_asker',
            [
                'id' => $booking->getId(),
                '_locale' => $userLocale,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $context = [
            'user' => $user,
            'offerer' => $offerer,
            'listing' => $listing,
            'booking' => $booking,
            'booking_request_url' => $bookingRequestUrl,
        ];

        $this->sendMessage($template, $context, $this->fromEmail, $user->getEmail());
    }

    /**
     * email is sent 24 hours after the end of the reservation.
     * so, that the asker can rate the offerer.
     *
     * @param Booking $booking
     *
     * @return void
     */
    public function sendReminderToRateOffererMessageToAsker(Booking $booking)
    {
        $user = $booking->getUser();
        $userLocale = $user->guessPreferredLanguage($this->locales, $this->locale);
        $listing = $booking->getListing();
        $offerer = $listing->getUser();
        $template = $this->templates['reminder_to_rate_offerer_asker'];

        $askerToOffererReviewUrl = $this->router->generate(
            'cocorico_dashboard_review_new',
            [
                'booking_id' => $booking->getId(),
                '_locale' => $userLocale
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $context = [
            'user' => $user,
            'offerer' => $offerer,
            'booking' => $booking,
            'asker_to_offerer_review_url' => $askerToOffererReviewUrl
        ];

        $this->sendMessage($template, $context, $this->fromEmail, $user->getEmail());
    }

    /**
     * email is sent if the asker cancels his reservation.
     *
     * @param Booking $booking
     *
     * @return void
     */
    public function sendBookingCanceledByAskerMessageToAsker(Booking $booking)
    {
        // TODO: Implement sendBookingCanceledByAskerMessageToAsker() method.
    }

    /**
     * email is sent to admin
     *
     * @param string $subject
     * @param string $message
     *
     * @return void
     */
    public function sendMessageToAdmin($subject, $message)
    {
        // TODO: Implement sendMessageToAdmin() method.
    }

    /**
     * @param string $templateName
     * @param array  $context
     * @param string $fromEmail
     * @param string $toEmail
     */
    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
        $user = null;
        $context['trans_domain'] = self::TRANS_DOMAIN;

        $context['user_locale'] = $this->locale;
        $context['locale'] = $this->locale;
        $context['app']['request']['locale'] = $this->locale;
        $context['user_timezone'] = $this->timezone;

        #user receiving the email
        if (isset($context['user'])) {
            /** @var User $user */
            $user = $context['user'];
            $context['user_locale'] = $user->guessPreferredLanguage($this->locales, $this->locale);
            $context['locale'] = $context['user_locale'];
            $context['app']['request']['locale'] = $context['user_locale'];
            $context['user_timezone'] = $user->getTimeZone();
        }

        if (isset($context['listing'])) {
            /** @var Listing $listing */
            $listing = $context['listing'];
            $translations = $listing->getTranslations();
            if ($translations->count() && isset($translations[$context['user_locale']])) {
                $slug = $translations[$context['user_locale']]->getSlug();
                $title = $translations[$context['user_locale']]->getTitle();
            } else {
                $slug = $listing->getSlug();
                $title = $listing->getTitle();
            }
            $context['listing_public_url'] = $this->router->generate(
                'cocorico_listing_show',
                [
                    '_locale' => $context['user_locale'],
                    'slug' => $slug,
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $context['listing_title'] = $title;
        }

        if (isset($context['booking'])) {
            $context['booking_time_range_title'] = $context['booking_time_range'] = '';

            if (!$this->timeUnitIsDay) {
                /** @var Booking $booking */
                $booking = $context['booking'];
                $context['booking_time_range_title'] = $this->translator->trans(
                    'booking.time_range.title',
                    array(),
                    'cocorico_mail',
                    $context['user_locale']
                );

                $timezone = $this->timezone;
                if ($user) {
                    if ($booking->getUser() == $user) {
                        $timezone = $booking->getTimeZoneAsker();
                    } elseif ($booking->getListing()->getUser() == $user) {
                        $timezone = $booking->getTimeZoneOfferer();
                    }
                }

                $startTime = clone $booking->getStartTime();
                $startTime->setTimezone(new \DateTimeZone($timezone));
                $endTIme = clone $booking->getEndTime();
                $endTIme->setTimezone(new \DateTimeZone($timezone));

                $context['booking_time_range'] .= $startTime->format('H:i') . " - " . $endTIme->format('H:i');
                $context['user_timezone'] = $timezone;
            }
        }

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