<?php

namespace IrishDan\NotificationBundle\Notification;

/**
 * Interface DatabaseNotificationInterface
 *
 * @package IrishDan\NotificationBundle\Notification
 */
interface DatabaseNotificationInterface
{
    /**
     * Get the notifiable entity that the notification belongs to.
     */
    public function getNotifiable();

    /**
     * Mark the notification as read.
     *
     * @return void
     */
    public function markAsRead();

    /**
     * @param \DateTime $date
     * @return mixed
     */
    public function setReadAt(\DateTime $date);

    /**
     * Determine if a notification has been read.
     *
     * @return bool
     */
    public function isRead();
}