<?php

namespace IrishDan\NotificationBundle\Broadcast;

use IrishDan\NotificationBundle\Channel\ChannelInterface;
use IrishDan\NotificationBundle\Notification\NotifiableInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

class Broadcaster
{
    protected $notifiable;
    protected $channel;
    protected $config;
    protected $channelName;

    public function __construct(NotifiableInterface $notifiable, ChannelInterface $channel, array $config)
    {
        $this->notifiable = $notifiable;
        $this->channel = $channel;
        $this->config = $config;
        $this->channelName = $channel->channelName();

        switch ($this->channelName) {
            case 'slack':
                // Set the data for Slack Broadcasts
                if (!empty($config['webhook'])) {
                    $notifiable->setSlackWebhook($config['webhook']);
                }
                break;

            case 'pusher':
                // Set data for pusher broadcasts
                if (!empty($config['channel_name'])) {
                    $notifiable->setPusherChannel($config['channel_name']);
                }
                break;
        }
    }

    public function broadcast(NotificationInterface $notification)
    {
        $notification->setNotifiable($this->notifiable);
        $notification->setChannel($this->channelName);

        // Format and send the broadcast
        $this->channel->formatAndDispatch($notification);
    }
}