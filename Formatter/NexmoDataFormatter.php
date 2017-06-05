<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Message\NexmoMessage;
use IrishDan\NotificationBundle\Notification\NotifiableInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

class NexmoDataFormatter extends BaseFormatter implements MessageFormatterInterface
{
    public function format(NotificationInterface $notification)
    {
        /** @var NotifiableInterface $notifiable */
        $notifiable = $notification->getNotifiable();
        $notificationData = $notification->getDataArray();

        if (!empty($this->twig) && $notification->getTemplate()) {
            $notificationData['body'] = $this->renderTwigTemplate($notificationData, $notifiable, $notification->getTemplate());
        }

        $data = new NexmoMessage();

        $data->setData($notificationData);

        // @TODO: Check for textable interface.

        $data->setTo($notifiable->getNumber());

        return $data;
    }
}