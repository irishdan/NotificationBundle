<?php

namespace IrishDan\NotificationBundle\Event;

use IrishDan\NotificationBundle\Notification\NotifiableInterface;
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
    /** @var NotifiableInterface */
    public $notifiable;
    /** @var NotificationInterface */
    public $notification;
    /** @var boolean */
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

    public function getNotifiable()
    {
        return $this->notifiable;
    }

    public function getNotification()
    {
        return $this->notification;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
