<?php

namespace IrishDan\NotificationBundle\Channel;

use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\Formatter\MessageFormatterInterface;
use IrishDan\NotificationBundle\Dispatcher\MessageDispatcherInterface;

interface ChannelInterface
{
    // @TODO: Used by default
    public function formatAndDispatch(NotificationInterface $notification);

    // @TODO: Allows for manual formatting and sending
    public function dispatch(MessageInterface $message);

    // @TODO: Allows for manual formatting and sending
    public function format(NotificationInterface $notification);

    // public function setFormatter(MessageFormatterInterface $formatter);

    // public function setDispatcher(MessageDispatcherInterface $dispatcher);
}