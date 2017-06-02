<?php

namespace IrishDan\NotificationBundle\Message;

/**
 * Class PusherMessage
 *
 * @package NotificationBundle\Message
 */
class PusherMessage extends BaseMessage
{
    /**
     * @var array
     */
    private $data = [];
    /**
     * @var
     */
    private $channelIdentifier;

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getChannelIdentifier()
    {
        return $this->channelIdentifier;
    }

    /**
     * @param mixed $identifier
     */
    public function setChannelIdentifier($identifier)
    {
        $this->channelIdentifier = $identifier;
    }
}
