<?php

namespace Cocorico\ContactBundle\Form\Handler\Frontend;

use Cocorico\ContactBundle\Entity\Contact;
use Cocorico\ContactBundle\Mailer\ContactMailer;
use Cocorico\ContactBundle\Model\Manager\ContactManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Handle Contact Form
 */
class ContactFormHandler
{
    protected $request;
    protected $contactManager;
    protected $contactMailer;

    /**
     * @param RequestStack    $requestStack
     * @param ContactManager  $contactManager
     * @param ContactMailer $contactMailer
     */
    public function __construct(RequestStack $requestStack, ContactManager $contactManager, ContactMailer $contactMailer)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->contactManager = $contactManager;
        $this->contactMailer = $contactMailer;
    }

    /**
     * Process form
     *
     * @param Form $form
     *
     * @return Contact|boolean
     */
    public function process(Form $form)
    {
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $this->request->isMethod('POST') && $form->isValid()) {
            return $this->onSuccess($form);
        }

        return false;
    }

    /**
     * @param Form $form
     * @return Contact
     */
    private function onSuccess(Form $form)
    {
        /** @var Contact $contact */
        $contact = $form->getData();
        $contact = $this->contactManager->save($contact);
        $this->contactMailer->sendContactMessage($contact);

        return $contact;
    }
}
