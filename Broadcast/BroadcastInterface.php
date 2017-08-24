<?php

namespace IrishDan\NotificationBundle\Broadcast;

use IrishDan\NotificationBundle\Notification\NotifiableInterface;
use IrishDan\NotificationBundle\SlackableInterface;


class Broadcast implements BroadcastNotifiableInterface, NotifiableInterface, SlackableInterface
{
    protected $slackWebhook;
    private $subscribedChannels = [
        'broadcast',
    ];

    public function getSubscribedChannels()
    {
        return $this->subscribedChannels;
    }

    public function isSubscribedToChannel($channel)
    {
        return in_array($this->subscribedChannels, $channel);
    }

    public function setSlackWebhook($webhook)
    {
        $this->slackWebhook = $webhook;
    }

    public function getSlackWebhook()
    {
        return $this->slackWebhook;
    }

    // @TODO: NotificationInteface Refactor out of
    public function getNotifiableDetailsForChannel($driver)
    {
        // TODO: Implement getNotifiableDetailsForChannel() method.
    }

    public function getEmail()
    {
        // TODO: Implement getEmail() method.
    }

    public function notifications()
    {
        // TODO: Implement notifications() method.
    }

    public function readNotifications()
    {
        // TODO: Implement readNotifications() method.
    }

    public function unreadNotifications()
    {
        // TODO: Implement unreadNotifications() method.
    }

    public function notify($instance)
    {
        // TODO: Implement notify() method.
    }
}
