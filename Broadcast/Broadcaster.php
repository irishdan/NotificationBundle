<?php

namespace IrishDan\NotificationBundle\Broadcast;

use IrishDan\NotificationBundle\Channel\ChannelInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

class Broadcaster
{
    protected $notifiable;
    protected $channel;
    protected $config;

    public function __construct(BroadcastNotifiableInterface $notifiable, ChannelInterface $channel, array $config)
    {
        $this->notifiable = $notifiable;
        $this->channel = $channel;

        // Set the data for Slack Broadcasts
        if (!empty($config['webhook'])) {
            $notifiable->setSlackWebhook($config['webhook']);
        }

        // Set data for pusher broadcasts
        if (!empty($config['channel_name'])) {
            $notifiable->setPusherChannel($config['channel_name']);
        }

        // @TODO: Set data for mailchimp Broadcasts
        // @TODO: Set data for drip Broadcasts
    }

    public function send(NotificationInterface $notification)
    {
        $notification->setNotifiable($this->notifiable);
        $notification->setChannel('slack');

        $message = $this->channel->format($notification);

        $this->channel->dispatch($message);
    }
}