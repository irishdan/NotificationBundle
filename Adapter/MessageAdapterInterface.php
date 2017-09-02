<?php

namespace IrishDan\NotificationBundle\Adapter;

use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

/**
 * Interface MessageAdapterInterface
 *
 * @package IrishDan\NotificationBundle\Adapter
 */
interface MessageAdapterInterface
{
    /**
     * Channels using each adapter need to be able to set configuration specific to that channel.
     * This allows for multiple channels using the same adapter, for example: pusher_chanel_1 and pusher_channel_2
     *
     * @param array $config
     * @return mixed
     */
    public function setConfiguration(array $config);

    /**
     * Channels using each adapter need to transfer channel name to the formatted message
     * so that the message can be dispatched asynchronously.
     *
     * @param $channelName
     * @return mixed
     */
    public function setChannelName($channelName);

    /**
     * Takes a Notification, formats the content and delivery data
     * and returns a Message object which a dispatcher dispatch.
     *
     * @param NotificationInterface $notification
     * @return MessageInterface
     */
    public function format(NotificationInterface $notification);

    /**
     * @param MessageInterface $message
     * @return boolean
     */
    public function dispatch(MessageInterface $message);
}