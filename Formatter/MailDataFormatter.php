<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Emailable;
use IrishDan\NotificationBundle\Exception\MessageFormatException;
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
        $notification->setChannel(self::CHANNEL);
        parent::format($notification);

        /** @var Emailable $notifiable */
        $notifiable = $notification->getNotifiable();
        if (!$notifiable instanceof Emailable) {
            throw new MessageFormatException(
                'Notifiable must implement Emailable interface in order to format email message'
            );
        }

        // Build the dispatch data array.
        $dispatchData = [
            'to'   => $notifiable->getEmail(),
            'from' => empty($this->mailConfiguration['default_sender']) ? '' : $this->mailConfiguration['default_sender'],
        ];

        $messageData = self::createMessagaData($notification->getDataArray());
        $message     = self::createMessage($dispatchData, $messageData, self::CHANNEL);

        return $message;
    }
}