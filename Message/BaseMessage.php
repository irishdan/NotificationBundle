<?php

namespace IrishDan\NotificationBundle\Message;

abstract class BaseMessage implements MessageInterface
{
    private $dispatchData;
    private $messageData;
    private $channel;

    public function getDispatchData()
    {
        return $this->dispatchData;
    }

    public function getMessageData()
    {
        return $this->messageData;
    }

    public function setDispatchData(array $data)
    {
        $this->dispatchData = $data;
    }

    public function setMessageData(array $data)
    {
        $this->messageData = $data;
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function setChannel($channel)
    {
        $this->channel = $channel;
    }
}