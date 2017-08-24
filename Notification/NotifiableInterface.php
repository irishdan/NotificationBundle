<?php

namespace IrishDan\NotificationBundle\Notification;

interface NotifiableInterface
{
    /**
     * Returns an array of channels the user has subscribed to
     *
     * @return array
     */
    public function getSubscribedChannels();

    /**
     * returns a boolean based on whether or not the notifiable is subscribed to the given channel
     *
     * @param $channel
     * @return boolean
     */
    public function isSubscribedToChannel($channel);

    /**
     * Get the entity's notifications.
     */
    // public function notifications();

    /**
     * Get the entity's read notifications.
     */
    // public function readNotifications();

    /**
     * Get the entity's unread notifications.
     */
    // public function unreadNotifications();
    // @TODO: Not sure if these methods should be here of is they are better as part of a service

    /**
     * Send the given notification.
     *
     * @param  mixed $instance
     * @return void
     */
    // public function notify($instance);

    /**
     * Returns the credentials needed for a particular user for a particular channel.
     * Eg if its a personal slack channel etc etc.
     * For example for the mail channel, the users email should be returned.
     *
     * @param  string $driver
     * @return mixed
     */
    // public function getNotifiableDetailsForChannel($driver);
}