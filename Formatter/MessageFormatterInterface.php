<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\Message\BaseMessage;

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