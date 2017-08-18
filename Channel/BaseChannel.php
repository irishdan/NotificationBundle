<?php

namespace IrishDan\NotificationBundle\Channel;

use IrishDan\NotificationBundle\Formatter\MessageFormatterInterface;
use IrishDan\NotificationBundle\Dispatcher\MessageDispatcherInterface;

abstract class BaseChannel implements ChannelInterface
{
    protected $channelEnabled;
    protected $channelConfiguration;
    protected $channel;
    protected $formatter;
    protected $dispatcher;

    public function __construct($channelEnabled = true, array $channelConfiguration = [], $channel = '')
    {
        $this->channelEnabled       = $channelEnabled;
        $this->channelConfiguration = $channelConfiguration;
        $this->channel              = $channel;
    }

    public function setDispatcher(MessageDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function setDataFormatter(MessageFormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }
}
