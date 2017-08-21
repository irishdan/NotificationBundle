<?php

namespace IrishDan\NotificationBundle\Event;

use IrishDan\NotificationBundle\Message\MessageInterface;
use Symfony\Component\EventDispatcher\Event;

class MessageCreatedEvent extends Event
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