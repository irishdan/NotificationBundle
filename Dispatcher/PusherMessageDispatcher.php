<?php

namespace IrishDan\NotificationBundle\Dispatcher;

use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\PusherManager;

class PusherMessageDispatcher implements MessageDispatcherInterface
{
    protected $pusherManager;

    public function __construct(PusherManager $pusherManager)
    {
        $this->pusherManager = $pusherManager;
    }

    public function dispatch(MessageInterface $message)
    {
        // Get the dispatch and message data from the message.
        $dispatchData = $message->getDispatchData();
        $messageData  = $message->getMessageData();

        // @TODO: Pusher channel

        $pusher = $this->pusherManager->getPusherClient();

        $pusherData = $data->getData();
        $channel    = [
            $this->pusherManager->getChannelName($data->getChannelIdentifier()),
        ];
        $event      = $this->pusherManager->getEvent();

        return $pusher->trigger($channel, $event, $pusherData);
    }
}