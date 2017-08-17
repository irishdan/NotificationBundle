<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Notification\NotificationInterface;
use Nexmo\Message\MessageInterface;

/**
 * Interface MessageFormatterInterface
 *
 * @package NotificationBundle\Formatter
 */
interface MessageFormatterInterface
{
    /**
     * Takes a Notification, formats the content and delivery data
     * and returns a Message object which a dispatcher dispatch.
     *
     * @param NotificationInterface $notification
     *
     * @return MessageInterface
     */
    public function format(NotificationInterface $notification);
}