<?php

namespace NotificationBundle\Event;

use NotificationBundle\Notification\NotificationInterface;
use Symfony\Component\EventDispatcher\Event;

class NotificationSendingEvent extends Event
{
    const NAME = 'notification.sending';
    private $notification;

    public function __construct(NotificationInterface $notification)
    {
        $this->notification = $notification;
    }

    public function getNotification()
    {
        return $this->notification;
    }

    public function debug()
    {
        return [
            'NotificationSending',
            $this->notification->getNotificationArray(),
            $this->notification->getChannels(),
        ];
    }
}
