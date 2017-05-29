<?php

namespace NotificationBundle\Channel;

use NotificationBundle\Notification\NotificationInterface;
use NotificationBundle\Formatter\MessageFormatterInterface;
use NotificationBundle\Dispatcher\MessageDispatcherInterface;

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