<?php

namespace IrishDan\NotificationBundle\Event;

use IrishDan\NotificationBundle\Notification\NotificationInterface;
use Symfony\Component\EventDispatcher\Event;

class NotificationReadyToFormatEvent extends Event
{
    const NAME = 'notification.ready_to_format';
    protected $notification;

    public function __construct(NotificationInterface $notification)
    {
        $this->notification = $notification;
    }
    
    public function getNotification()
    {
        return $this->notification;
    }
}