<?php

namespace IrishDan\NotificationBundle\Event;

use IrishDan\NotificationBundle\Message\MessageInterface;
use Symfony\Component\EventDispatcher\Event;

class MessageDispatchedEvent extends Event
{
    const NAME = 'notification.dispatched';
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