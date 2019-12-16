<?php

namespace Cocorico\MessageBundle\Event;

use Cocorico\MessageBundle\Mailer\TwigSwiftMailer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MessageSubscriber implements EventSubscriberInterface
{
    protected $mailer;

    public function __construct(TwigSwiftMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param MessageEvent             $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function onMessagePostSend(MessageEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $thread = $event->getThread();
        $recipient = $event->getRecipient();
        $sender = $event->getSender();

        $this->mailer->sendNewThreadMessageToUser($thread->getId(), $recipient, $sender);
    }


    public static function getSubscribedEvents()
    {
        return array(
            MessageEvents::MESSAGE_POST_SEND => array('onMessagePostSend', 1),
        );
    }

}