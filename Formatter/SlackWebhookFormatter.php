<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Message\Message;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\Slackable;
use IrishDan\NotificationBundle\Textable;

class SlackWebhookFormatter implements MessageFormatterInterface
{
    public function format(NotificationInterface $notification)
    {
        $message = new Message();
        // $notificationData = $notification->getDataArray();

        /** @var Slackable $notifiable */
        $notifiable = $notification->getNotifiable();
        if (!$notifiable instanceof Slackable) {
            throw new \RuntimeException('Notifiable mustimplement Slackable interface in order to send SMS');
        }

        // Build the dispatch data array.
        $dispatchData = [
            'webhook' => $notifiable->getSlackWebhook(),
        ];

        // Build the message data array.
        $messageData         = [];
        $messageData['body'] = 'Its time for a pint Mullin!!!!';

        $message->setDispatchData($dispatchData);
        $message->setMessageData($messageData);

        return $message;
    }
}