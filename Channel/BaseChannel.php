<?php

namespace IrishDan\NotificationBundle\Channel;

use IrishDan\NotificationBundle\Adapter\MessageAdapterInterface;

abstract class BaseChannel implements ChannelInterface
{
    protected $channelConfiguration;
    protected $channel;
    protected $adapter;

    public function __construct(array $channelConfiguration = [], $channel = '')
    {
        $this->channelConfiguration = $channelConfiguration;
        $this->channel = $channel;
    }

    public function setAdapter(MessageAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function channelName()
    {
        return $this->channel;
    }
}
