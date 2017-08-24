<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\DatabaseNotifiableInterface;
use IrishDan\NotificationBundle\Exception\MessageFormatException;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

class DatabaseDataFormatter extends BaseFormatter implements MessageFormatterInterface
{
    const CHANNEL = 'database';

    public function format(NotificationInterface $notification)
    {
        $notification->setChannel(self::CHANNEL);
        parent::format($notification);

        // /** @var DatabaseNotifiableInterface $notifiable */
        $notifiable = $notification->getNotifiable();
        if (!$notifiable instanceof DatabaseNotifiableInterface) {
            throw new MessageFormatException(
                'Notifiable must implement DatabaseNotifiableInterface interface in order to format email message'
            );
        }

        // Build the dispatch data array.
        $dispatchData = [
            'id' => $notifiable->getId(),
        ];

        $messageData = self::createMessagaData($notification->getDataArray());
        $message = self::createMessage($dispatchData, $messageData, self::CHANNEL);

        return $message;
    }
}