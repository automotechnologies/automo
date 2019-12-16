<?php

namespace Cocorico\UserBundle\Model;

use Cocorico\UserBundle\Entity\User;

interface BookingDepositRefundAsOffererInterface
{
    /**
     * @param User $offerer
     * @return mixed
     */
    public function setOfferer(User $offerer);

    /**
     * @return User
     */
    public function getOfferer();
}