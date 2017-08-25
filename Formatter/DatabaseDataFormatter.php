<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\DatabaseNotifiableInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

/**
 * Class DatabaseDataFormatter
 *
 * @package IrishDan\NotificationBundle\Formatter
 */
class DatabaseDataFormatter extends BaseFormatter implements MessageFormatterInterface
{
    const CHANNEL = 'database';

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
}