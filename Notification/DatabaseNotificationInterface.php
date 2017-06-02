<?php

namespace IrishDan\NotificationBundle\Notification;

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

    public function setReadAt(\DateTime $date); // @TODO: We dont need both.

    /**
     * Determine if a notification has been read.
     *
     * @return bool
     */
    public function isRead();

    /**
     * Determine if a notification has not been read.
     *
     * @return bool
     */
    public function isUnread();
}