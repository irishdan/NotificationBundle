<?php

namespace IrishDan\NotificationBundle\Broadcast;

use IrishDan\NotificationBundle\Notification\NotifiableInterface;
use IrishDan\NotificationBundle\PusherableInterface;
use IrishDan\NotificationBundle\SlackableInterface;

class Broadcast implements BroadcastNotifiableInterface, NotifiableInterface, SlackableInterface, PusherableInterface
{
    protected $slackWebhook;
    protected $pusherChannel;
    protected $subscribedChannels = [
        'slack',
        'pusher',
    ];

    public function setSlackWebhook($webhook)
    {
        $this->slackWebhook = $webhook;
    }

    public function getSlackWebhook()
    {
        return $this->slackWebhook;
    }

    public function setPusherChannel($pusherChannel)
    {
        $this->pusherChannel = $pusherChannel;
    }

    public function getPusherChannel()
    {
        return $this->pusherChannel;
    }

    public function getSubscribedChannels()
    {
        $this->subscribedChannels;
    }

    public function isSubscribedToChannel($channel)
    {
        return in_array($this->subscribedChannels, $channel);
    }

    public function getPusherChannelSuffix()
    {
        return 'broadcast';
    }

    public function getData()
    {
        return [
            'pusher_channel' => $this->pusherChannel,
            'slack_webhook' => $this->slackWebhook,
        ];
    }
}