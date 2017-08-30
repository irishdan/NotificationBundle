<?php

namespace IrishDan\NotificationBundle\Adapter;


use IrishDan\NotificationBundle\DatabaseNotifiableInterface;
use IrishDan\NotificationBundle\DatabaseNotificationManager;
use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

class DatabaseMessageAdapter extends BaseMessageAdapter implements MessageAdapterInterface
{
    const CHANNEL = 'database';

    protected $databaseNotificationManager;

    public function __construct(DatabaseNotificationManager $databaseNotificationManager)
    {
        $this->databaseNotificationManager = $databaseNotificationManager;
    }

    /**
     * Generates a message object
     *
     * @param NotificationInterface $notification
     * @return \IrishDan\NotificationBundle\Message\Message
     */
    public function format(NotificationInterface $notification)
    {
        $notification->setChannel(self::CHANNEL);
        parent::format($notification);

        /** @var DatabaseNotifiableInterface $notifiable */
        $notifiable = $notification->getNotifiable();
        if (!$notifiable instanceof DatabaseNotifiableInterface) {
            $this->createFormatterException(DatabaseNotifiableInterface::class, self::CHANNEL);
        }

        // Build the dispatch data array.
        $dispatchData = [
            'id' => $notifiable->getIdentifier(),
            'notifiable' => $notifiable,
            'uuid' => $notification->getUuid(),
            'type' => get_class($notification),
        ];

        $messageData = self::createMessagaData($notification->getDataArray());

        return self::createMessage($dispatchData, $messageData, self::CHANNEL);
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