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

    public function getTitle()
    {
        if (array_key_exists('title', $this->messageData)) {
            return $this->messageData['title'];
        }

        return 'NA';
    }

    public function getRecipient()
    {
        $recipientKeys = [
            'to',
            'id',
            'channel',
            'webhook',
        ];

        foreach ($recipientKeys as $key) {
            if (array_key_exists($key, $this->dispatchData)) {
                return $this->dispatchData[$key];
            }
        }

        return 'NA';
    }
}