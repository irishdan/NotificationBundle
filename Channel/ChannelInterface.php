<?php

namespace IrishDan\NotificationBundle\Channel;

use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\Formatter\MessageFormatterInterface;
use IrishDan\NotificationBundle\Dispatcher\MessageDispatcherInterface;

interface ChannelInterface
{
    public function dispatch(MessageInterface $message);

    public function format(NotificationInterface $notification);

    // public function setFormatter(MessageFormatterInterface $formatter);

    // public function setDispatcher(MessageDispatcherInterface $dispatcher);
}