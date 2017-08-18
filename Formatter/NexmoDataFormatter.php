<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Message\Message;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\Textable;

class NexmoDataFormatter extends BaseFormatter implements MessageFormatterInterface
{
    protected $nexmoConfiguration;

    public function __construct(array $nexmoConfiguration = [])
    {
        $this->nexmoConfiguration = $nexmoConfiguration;
    }

    public function format(NotificationInterface $notification)
    {
        $message          = new Message();
        $notificationData = $notification->getDataArray();

        // The User/Notifiable must implement Textable interface in order to receive SMSs
        $notifiable = $notification->getNotifiable();
        if (!$notifiable instanceof Textable) {
            throw new \RuntimeException('Notifiable mustimplement Textable interface in order to send SMS');
        }

        // Build the dispatch data array.
        $dispatchData = [
            'to'   => $notifiable->getNumber(),
            'from' => empty($this->nexmoConfiguration['from']) ? '' : $this->nexmoConfiguration['from'],
        ];

        // Build the message data array.
        $messageData = [];
        // @TODO: Works but not when body is dynamic??
        $messageData['body'] = 'A Hoi hoi! Marcus!';
        // if (!empty($this->twig) && $notification->getTemplate()) {
        //     $messageData['body'] = $this->renderTwigTemplate(
        //         $notificationData,
        //         $notifiable,
        //         $notification->getTemplate()
        //     );
        // }

        $message->setDispatchData($dispatchData);
        $message->setMessageData($messageData);

        return $message;
    }
}