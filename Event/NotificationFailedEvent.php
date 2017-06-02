<?php

namespace IrishDan\NotificationBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class NotificationFailedEvent extends Event
{
    const NAME = 'notification.failed';
    public $notification;
    public $data = [];

    public function __construct($notification, $data = [])
    {
        $this->notification = $notification;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
