<?php

namespace IrishDan\NotificationBundle\Dispatcher;

use IrishDan\NotificationBundle\PusherManager;
use IrishDan\NotificationBundle\Message\BaseMessage;

class PusherMessageDispatcher implements MessageDispatcherInterface
{
    protected $pusherManager;

    public function __construct(PusherManager $pusherManager)
    {
        $this->pusherManager = $pusherManager;
    }

    public function dispatch(BaseMessage $data)
    {
        $pusher = $this->pusherManager->getPusherClient();

        $pusherData = $data->getData();
        $channel = [
            $this->pusherManager->getChannelName($data->getChannelIdentifier()),
        ];
        $event = $this->pusherManager->getEvent();

        return $pusher->trigger($channel, $event, $pusherData);
    }
}