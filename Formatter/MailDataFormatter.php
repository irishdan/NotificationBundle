<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Emailable;
use IrishDan\NotificationBundle\Exception\MessageFormatException;
use IrishDan\NotificationBundle\Message\Message;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

class MailDataFormatter extends BaseFormatter implements MessageFormatterInterface
{
    const CHANNEL = 'mail';
    private $mailConfiguration;

    public function __construct(array $mailConfiguration)
    {
        $this->mailConfiguration = $mailConfiguration;
    }

    public function format(NotificationInterface $notification)
    {

        $message          = new Message();
        $notificationData = $notification->getDataArray();

        /** @var Emailable $notifiable */
        $notifiable = $notification->getNotifiable();
        if (!$notifiable instanceof Emailable) {
            throw new MessageFormatException(
                'Notifiable must implement Emailable interface in order to format email message'
            );
        }

        // Set the channel key
        $message->setChannel(self::CHANNEL);

        // Build the dispatch data array.
        $dispatchData = [
            'to'   => $notifiable->getEmail(),
            'from' => empty($this->mailConfiguration['default_sender']) ? '' : $this->mailConfiguration['default_sender'],
        ];

        // Build the message data array.
        $messageData          = [];
        $messageData['body']  = empty($notificationData['body']) ? '' : $notificationData['body'];
        $messageData['title'] = empty($notificationData['title']) ? '' : $notificationData['title'];

        // if (!empty($this->twig) && $notification->getTemplate()) {
        //     $notificationData['body'] = $this->renderTwigTemplate($notificationData, $notifiable, $notification->getTemplate());
        // }

        $message->setDispatchData($dispatchData);
        $message->setMessageData($messageData);

        return $message;
    }
}