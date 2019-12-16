<?php

namespace Cocorico\MessageBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;

/**
 * Interface MailerInterface
 *
 */
interface MailerInterface
{
    /**
     * Send new message notification email
     *
     * @param integer       $threadId
     * @param UserInterface $recipient
     * @param UserInterface $sender
     *
     * @return void
     */
    public function sendNewThreadMessageToUser($threadId, UserInterface $recipient, UserInterface $sender);

}
