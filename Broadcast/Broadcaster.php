<?php

namespace IrishDan\NotificationBundle\Broadcast;

use IrishDan\NotificationBundle\Channel\ChannelInterface;
use IrishDan\NotificationBundle\Notification\NotifiableInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

/**
 * Class Broadcaster
 *
 * @package IrishDan\NotificationBundle\Broadcast
 */
class Broadcaster
{
    protected $notifiable;
    protected $channel;
    protected $config;
    protected $channelName;

    /**
     * Broadcaster constructor.
     *
     * @param NotifiableInterface $notifiable
     * @param ChannelInterface    $channel
     * @param array               $config
     */
    public function __construct(NotifiableInterface $notifiable, ChannelInterface $channel, array $config)
    {
        // Set the data for Slack Broadcasts
        if (!empty($config['webhook'])) {
            $notifiable->setSlackWebhook($config['webhook']);
        }

        // Set data for pusher broadcasts
        if (!empty($config['channel_name'])) {
            $notifiable->setPusherChannel($config['channel_name']);
        }

        $this->notifiable = $notifiable;
        $this->channel = $channel;
        $this->config = $config;
        $this->channelName = $channel->channelName();
    }

    /**
     * @param NotificationInterface $notification
     */
    public function broadcast(NotificationInterface $notification)
    {
        $notification->setNotifiable($this->notifiable);
        $notification->setChannel($this->channelName);

        // Format and send the broadcast.
        $this->channel->setDispatchToEvent(false);
        $this->channel->formatAndDispatch($notification);
    }
}