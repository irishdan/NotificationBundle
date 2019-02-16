<?php

namespace IrishDan\NotificationBundle\Channel;

use IrishDan\NotificationBundle\Adapter\MessageAdapterInterface;

/**
 * Class BaseChannel
 *
 * @package IrishDan\NotificationBundle\Channel
 */
abstract class BaseChannel implements ChannelInterface
{
    /**
     * @var array
     */
    protected $channelConfiguration;
    /**
     * @var
     */
    protected $channelName;
    /**
     * @var
     */
    protected $adapter;

    public function __construct(array $channelConfiguration = [], $channelName = null, MessageAdapterInterface $adapter = null)
    {
        $this->channelConfiguration = $channelConfiguration;

        $this->channelName = $channelName;

        if (!empty($adapter)) {
            // The adapter needs the channel name and the configurations.
            $adapter->setChannelName($channelName);
            $adapter->setConfiguration($channelConfiguration);

            $this->adapter = $adapter;
        }
    }

    /**
     * @param array $channelConfiguration
     */
    public function setChannelConfiguration(array $channelConfiguration): void
    {
        $this->channelConfiguration = $channelConfiguration;
    }

    /**
     * @param mixed $channelName
     */
    public function setChannelName(string $channelName): void
    {
        $this->channelName = $channelName;
    }

    public function setAdapter(MessageAdapterInterface $adapter): void
    {
        $this->adapter = $adapter;
    }

    /**
     * @return mixed
     */
    public function channelName()
    {
        return $this->channelName;
    }

    public function getConfiguration()
    {
        return $this->channelConfiguration;
    }
}
