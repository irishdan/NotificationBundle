<?php

namespace IrishDan\NotificationBundle\Broadcast;

use IrishDan\NotificationBundle\Notification\NotifiableInterface;
use IrishDan\NotificationBundle\SlackableInterface;

class Broadcast implements BroadcastNotifiableInterface, NotifiableInterface, SlackableInterface
{
    protected $slackWebhook;
    protected $subscribedChannels = [
        'slack',
    ];

    public function setSlackWebhook($webhook)
    {
        $this->slackWebhook = $webhook;
    }

    public function getSlackWebhook()
    {
        return $this->slackWebhook;
    }

    public function setPusherChannel($channelName)
    {
        // TODO: Implement setPusherChannel() method.
    }

    public function getPusherChannel()
    {
        // TODO: Implement getPusherChannel() method.
    }

    public function getSubscribedChannels()
    {
        $this->subscribedChannels;
    }

    public function isSubscribedToChannel($channel)
    {
        return in_array($this->subscribedChannels, $channel);
    }
}