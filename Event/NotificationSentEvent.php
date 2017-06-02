<?php

namespace IrishDan\NotificationBundle\Event;

use IrishDan\NotificationBundle\Notification\NotificationInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class NotificationSent
 *
 * @package NotificationBundle\Events
 */
class NotificationSentEvent extends Event
{
    const NAME = 'notification.sent';
    /**
     * The notifiable entity who received the notification.
     *
     * @var mixed
     */
    public $notifiable;
    /**
     * The notification instance.
     *
     * @var \NotificationBundle\Notification
     */
    public $notification;
    /**
     * The channel's response.
     *
     * @var mixed
     */
    public $response;

    /**
     * NotificationSent constructor.
     *
     * @param NotificationInterface $notification
     * @param null                  $response
     */
    public function __construct(NotificationInterface $notification, $response = null)
    {
        $this->response = $response;
        $this->notification = $notification;
    }

    /**
     * @return mixed
     */
    public function getNotifiable()
    {
        return $this->notifiable;
    }

    /**
     * @return \NotificationBundle\Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }
}
