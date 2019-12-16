<?php

namespace Cocorico\UserBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;

/**
 * Interface MailerInterface
 *
 */
interface MailerInterface
{
    /**
     * Send an email to a user after successful registration
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function sendAccountCreatedMessageToUser(UserInterface $user);

    /**
     * Send an email to a user to confirm his account creation
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function sendAccountCreationConfirmationMessageToUser(UserInterface $user);

    /**
     * Send password resetting email
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function sendResettingEmailMessageToUser(UserInterface $user);
}
