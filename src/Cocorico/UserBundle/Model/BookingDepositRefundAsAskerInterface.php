<?php

namespace Cocorico\UserBundle\Model;

use Cocorico\UserBundle\Entity\User;

interface BookingDepositRefundAsAskerInterface
{
    /**
     * @param User $asker
     * @return mixed
     */
    public function setAsker(User $asker);

    /**
     * @return User
     */
    public function getAsker();
}