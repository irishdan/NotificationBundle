<?php

namespace IrishDan\NotificationBundle\Channel;

use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

/**
 * Interface ChannelInterface
 *
 * @package IrishDan\NotificationBundle\Channel
 */
interface ChannelInterface
{
    /**
     * Formats a Notification into a Message and then dispatches the Message.
     * Used by the channel manager.
     *
     * @param NotificationInterface $notification
     * @return boolean
     */
    public function formatAndDispatch(NotificationInterface $notification);
}