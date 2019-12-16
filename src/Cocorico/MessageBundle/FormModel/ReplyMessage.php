<?php

namespace Cocorico\MessageBundle\FormModel;

use FOS\MessageBundle\FormModel\AbstractMessage;
use FOS\MessageBundle\Model\ThreadInterface;

class ReplyMessage extends AbstractMessage
{
    /**
     * The thread we reply to
     *
     * @var ThreadInterface
     */
    protected $thread;

    /**
     * @return ThreadInterface
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * @param  ThreadInterface $thread
     * @return $this
     */
    public function setThread(ThreadInterface $thread)
    {
        $this->thread = $thread;

        return $this;
    }
}
