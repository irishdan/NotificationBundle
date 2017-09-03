<?php

namespace IrishDan\NotificationBundle;

/**
 * Class PusherChannel
 *
 * @package IrishDan\NotificationBundle
 */
class PusherChannel
{
    /**
     * @var
     */
    private $channelName;
    /**
     * @var
     */
    private $socketId;

    /**
     * PusherChannel constructor.
     *
     * @param $channelName
     * @param $socketId
     */
    public function __construct($channelName, $socketId)
    {
        $this->channelName = $channelName;
        $this->socketId = $socketId;
    }

    /**
     * @return mixed
     */
    public function getChannelName()
    {
        return $this->channelName;
    }

    /**
     * @param mixed $channelName
     */
    public function setChannelName($channelName)
    {
        $this->channelName = $channelName;
    }

    /**
     * @return mixed
     */
    public function getSocketId()
    {
        return $this->socketId;
    }

    /**
     * @param mixed $socketId
     */
    public function setSocketId($socketId)
    {
        $this->socketId = $socketId;
    }
}