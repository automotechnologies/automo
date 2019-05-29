<?php

namespace Cocorico\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Class AccountConfirmation
 * @package Cocorico\UserBundle\Entity
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class AccountConfirmation
{
    use ORMBehaviors\Timestampable\Timestampable;


}