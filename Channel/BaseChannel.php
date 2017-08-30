<?php

namespace IrishDan\NotificationBundle\Channel;

use IrishDan\NotificationBundle\Adapter\MessageAdapterInterface;

abstract class BaseChannel implements ChannelInterface
{
    protected $channelEnabled;
    protected $channelConfiguration;
    protected $channel;
    protected $adapter;

    public function __construct($channelEnabled = true, array $channelConfiguration = [], $channel = '')
    {
        $this->channelEnabled = $channelEnabled;
        $this->channelConfiguration = $channelConfiguration;
        $this->channel = $channel;
    }

    public function setAdapter(MessageAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
}
