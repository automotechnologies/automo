<?php

namespace Cocorico\UserBundle\Model;

use Cocorico\UserBundle\Entity\User;

interface ListingAlertInterface
{
    /**
     * @param User $user
     * @return mixed
     */
    public function setUser(User $user);

    /**
     * @return User
     */
    public function getUser();

//    public function getName();

}