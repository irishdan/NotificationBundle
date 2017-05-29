<?php

namespace NotificationBundle;

class PusherChannel
{
    private $channelName;
    private $socketId;

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