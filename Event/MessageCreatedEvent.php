<?php

namespace IrishDan\NotificationBundle\Event;

use IrishDan\NotificationBundle\Message\MessageInterface;

class MessageCreatedEvent
{
    const NAME = 'notification.message_created';
    protected $message;

    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }
}