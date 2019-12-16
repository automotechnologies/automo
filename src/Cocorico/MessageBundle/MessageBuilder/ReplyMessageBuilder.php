<?php

namespace Cocorico\MessageBundle\MessageBuilder;

use FOS\MessageBundle\MessageBuilder\AbstractMessageBuilder;

/**
 * Fluent interface message builder for reply to a thread
 *
 *
 */
class ReplyMessageBuilder extends AbstractMessageBuilder
{

    /**
     * Sets $createdAt message.
     *
     * @param  date
     * @return $this (fluent interface)
     */
    public function setCreatedAt($createdAt)
    {
        $this->message->setCreatedAt($createdAt);

        return $this;
    }

}
