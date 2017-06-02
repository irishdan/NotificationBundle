<?php

namespace IrishDan\NotificationBundle\Channel;

use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\Formatter\MessageFormatterInterface;
use IrishDan\NotificationBundle\Dispatcher\MessageDispatcherInterface;

interface ChannelInterface
{
    /**
     * Send the given notification.
     *
     * @param $notification NotificationInterface
     */
    public function send(NotificationInterface $notification);

    public function setDataFormatter(MessageFormatterInterface $formatter);

    public function setDispatcher(MessageDispatcherInterface $dispatcher);
}