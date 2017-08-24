<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Exception\MessageFormatException;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\TextableInterface;

class NexmoDataFormatter extends BaseFormatter implements MessageFormatterInterface
{
    const CHANNEL = 'nexmo';
    protected $nexmoConfiguration;

    public function __construct(array $nexmoConfiguration = [])
    {
        $this->nexmoConfiguration = $nexmoConfiguration;
    }

    public function format(NotificationInterface $notification)
    {
        $notification->setChannel(self::CHANNEL);
        parent::format($notification);

        /** @var TextableInterface $notifiable */
        $notifiable = $notification->getNotifiable();
        if (!$notifiable instanceof TextableInterface) {
            throw new MessageFormatException(
                'Notifiable must implement TextableInterface interface in order to format email message'
            );
        }

        // Build the dispatch data array.
        $dispatchData = [
            'to' => $notifiable->getNumber(),
            'from' => empty($this->nexmoConfiguration['from']) ? '' : $this->nexmoConfiguration['from'],
        ];

        $messageData = self::createMessagaData($notification->getDataArray());
        $message = self::createMessage($dispatchData, $messageData, self::CHANNEL);

        return $message;
    }
}