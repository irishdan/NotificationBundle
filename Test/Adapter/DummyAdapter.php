<?php

namespace IrishDan\NotificationBundle\Test\Adapter;

use IrishDan\NotificationBundle\Adapter\BaseMessageAdapter;
use IrishDan\NotificationBundle\Adapter\MessageAdapterInterface;
use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

class DummyAdapter extends BaseMessageAdapter implements MessageAdapterInterface
{
    public function format(NotificationInterface $notification)
    {
        $notification->setChannel($this->channelName);
        parent::format($notification);

        $dispatchData = [];
        $messageData = self::createMessagaData([]);

        return self::createMessage($dispatchData, $messageData, $this->channelName);
    }

    public function dispatch(MessageInterface $message)
    {
        return true;
    }

    public function getChannelName()
    {
        return $this->channelName;
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }
}