<?php

namespace NotificationBundle\Formatter;

use NotificationBundle\Notification\NotificationInterface;
use NotificationBundle\Message\BaseMessage;

/**
 * Interface MessageFormatterInterface
 *
 * @package NotificationBundle\Formatter
 */
interface MessageFormatterInterface
{
    /**
     * @param NotificationInterface $notification
     * @return BaseMessage
     */
    public function format(NotificationInterface $notification);
}