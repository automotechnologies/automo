<?php

namespace Cocorico\ContactBundle\Model\Manager;

use Cocorico\ContactBundle\Entity\Contact;
use Doctrine\ORM\EntityManager;

class ContactManager extends BaseManager
{
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param  Contact $contact
     * @return Contact
     */
    public function save(Contact $contact)
    {
        $this->persistAndFlush($contact);

        return $contact;
    }
}
