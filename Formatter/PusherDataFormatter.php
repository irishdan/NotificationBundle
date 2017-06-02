<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Notification\NotifiableInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\Message\PusherMessage;

class PusherDataFormatter extends BaseFormatter implements MessageFormatterInterface
{
    public function format(NotificationInterface $notification)
    {
        /** @var NotifiableInterface $notifiable */
        $notifiable = $notification->getNotifiable();
        $notificationData = $notification->getDataArray();

        if (!empty($this->twig) && $notification->getTemplate()) {
            $notificationData['body'] = $this->renderTwigTemplate($notificationData, $notifiable, $notification->getTemplate());
        }

        $data = new PusherMessage();

        $data->setData($notificationData);
        $data->setChannelIdentifier($notifiable->getNotifiableDetailsForChannel('pusher'));

        return $data;
    }
}