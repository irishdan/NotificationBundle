<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Message\MailMessage;
use IrishDan\NotificationBundle\Message\Message;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

class MailDataFormatter extends BaseFormatter implements MessageFormatterInterface
{
    private $mailConfiguration;

    public function __construct(array $mailConfiguration)
    {
        $this->mailConfiguration = $mailConfiguration;
    }

    public function format(NotificationInterface $notification)
    {
        $message          = new Message();
        $notificationData = $notification->getDataArray();

        $notifiable = $notification->getNotifiable();
        // Build the dispatch data array.
        $dispatchData = [
            'to'   => $notifiable->getEmail(),
            'from' => empty($this->mailConfiguration['default_sender']) ? 'dan@nomadapi.io' : $this->mailConfiguration['default_sender'],
        ];

        // Build the message data array.
        $messageData            = [];
        $messageData['body']    = 'A Hoi hoi! Dan!';
        $messageData['subject'] = 'E-Mail from Nomad';
        // if (!empty($this->twig) && $notification->getTemplate()) {
        //     $notificationData['body'] = $this->renderTwigTemplate($notificationData, $notifiable, $notification->getTemplate());
        // }

        $message->setDispatchData($dispatchData);
        $message->setMessageData($messageData);

        return $message;
    }
}