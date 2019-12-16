<?php

namespace Cocorico\MessageBundle\Event;


class MessageEvents
{
    /**
     * The MESSAGE_POST_SEND event occurs after a new message has been send.
     *
     * This event allows you to add functionality (send mail, sms, ...) after a new message has been send.
     * The event listener method receives a Cocorico\MessageBundle\Event\MessageEvent instance.
     */
    const MESSAGE_POST_SEND = 'cocorico_message.post_send';
}