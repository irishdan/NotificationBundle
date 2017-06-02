<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Message\MailMessage;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

class MailDataFormatter extends BaseFormatter implements MessageFormatterInterface
{
    private $mailChannelConfiguration;

    public function __construct(array $mailChannelConfiguration)
    {
        $this->mailChannelConfiguration = $mailChannelConfiguration;
    }

    public function format(NotificationInterface $notification)
    {
        $notifiable = $notification->getNotifiable();
        $notificationData = $notification->getDataArray();

        if (!empty($this->twig) && $notification->getTemplate()) {
            $notificationData['body'] = $this->renderTwigTemplate($notificationData, $notifiable, $notification->getTemplate());
        }

        $message = $this->createMailMessage($notificationData, $notifiable);

        return $message;
    }

    protected function createMailMessage($data, $user)
    {
        $message = new MailMessage();
        $message->setSubject($data['title']);
        $message->setBody($data['body']);

        if (empty($data['sender'])) {
            $message->setFrom($this->mailChannelConfiguration['default_sender']);
        } else {
            $message->setFrom($data['sender']);
        }

        $message->setTo($user->getEmail());

        return $message;
    }
}