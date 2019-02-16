<?php

namespace IrishDan\NotificationBundle\Message;

/**
 * Class BaseMessage
 *
 * @package IrishDan\NotificationBundle\Message
 */
abstract class BaseMessage implements MessageInterface
{
    private $dispatchData;
    private $messageData;
    private $channel;
    private $status;

    /**
     * @return mixed
     */
    public function getDispatchData()
    {
        return $this->dispatchData;
    }

    /**
     * @return mixed
     */
    public function getMessageData()
    {
        return $this->messageData;
    }

    /**
     * @param array $data
     */
    public function setDispatchData(array $data)
    {
        $this->dispatchData = $data;
    }

    /**
     * @param array $data
     */
    public function setMessageData(array $data)
    {
        $this->messageData = $data;
    }

    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        if (array_key_exists('title', $this->messageData)) {
            return $this->messageData['title'];
        }

        return 'NA';
    }

    /**
     * @return string
     */
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

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }
}