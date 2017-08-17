<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Message\DatabaseMessage;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

class DatabaseDataFormatter extends BaseFormatter implements MessageFormatterInterface
{
    public function format(NotificationInterface $notification)
    {
        $notificationData = $notification->getDataArray();
        $notifiable = $notification->getNotifiable();

        if (!empty($this->twig) && $notification->getTemplate()) {
            $notificationData['body'] = $this->renderTwigTemplate($notificationData, $notifiable, $notification->getTemplate());
        }

        $message = new DatabaseMessage();

        // Format data for databaseNotification
        $message->setUuid($notification->getUuid());
        $message->setData(serialize($notificationData));
        $message->setType(get_class($notification));
        $message->setNotifiable($notification->getNotifiable());

        return $message;
    }
}