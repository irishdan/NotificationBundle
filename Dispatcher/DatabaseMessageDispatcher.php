<?php

namespace IrishDan\NotificationBundle\Dispatcher;

use IrishDan\NotificationBundle\DatabaseNotificationManager;
use IrishDan\NotificationBundle\Message\MessageInterface;

class DatabaseMessageDispatcher implements MessageDispatcherInterface
{
    protected $databaseNotificationManager;

    public function __construct(DatabaseNotificationManager $databaseNotificationManager)
    {
        $this->databaseNotificationManager = $databaseNotificationManager;
    }

    public function dispatch(MessageInterface $message)
    {
        $dispatchData = $message->getDispatchData();
        $messageData = $message->getMessageData();
        $data = $dispatchData + $messageData;

        $databaseNotification = $this->databaseNotificationManager->createDatabaseNotification($data);
        if ($databaseNotification) {
            return true;
        }

        return false;
    }
}