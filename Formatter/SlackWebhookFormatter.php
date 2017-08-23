<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Exception\MessageFormatException;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\Slackable;

class SlackWebhookFormatter extends BaseFormatter implements MessageFormatterInterface
{
    const CHANNEL = 'slack';

    public function format(NotificationInterface $notification)
    {
        $notification->setChannel(self::CHANNEL);
        parent::format($notification);

        /** @var Slackable $notifiable */
        $notifiable = $notification->getNotifiable();
        if (!$notifiable instanceof Slackable) {
            throw new MessageFormatException(
                'Notifiable must implement Slackable interface in order to format email message'
            );
        }

        // Build the dispatch data array.
        $dispatchData = [
            'webhook' => $notifiable->getSlackWebhook(),
        ];

        $messageData = self::createMessagaData($notification->getDataArray());
        $message     = self::createMessage($dispatchData, $messageData, self::CHANNEL);

        return $message;
    }
}