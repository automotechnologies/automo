<?php

namespace Cocorico\ContactBundle\Mailer;

use Cocorico\ContactBundle\Entity\Contact;

interface MailerInterface
{
    /**
     * email is sent to admin
     *
     * @param Contact $contact
     *
     * @return void
     */
    public function sendContactMessage(Contact $contact);
}
