<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\EmailableInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

/**
 * Class MailDataFormatter
 *
 * @package IrishDan\NotificationBundle\Formatter
 */
class MailDataFormatter extends BaseFormatter implements MessageFormatterInterface
{
    const CHANNEL = 'mail';
    private $mailConfiguration;

    public function __construct(array $mailConfiguration)
    {
        $this->mailConfiguration = $mailConfiguration;
    }

    /**
     * Generates a Message object
     *
     * @param NotificationInterface $notification
     * @return \IrishDan\NotificationBundle\Message\Message
     */
    public function format(NotificationInterface $notification)
    {
        $notification->setChannel(self::CHANNEL);
        parent::format($notification);

        /** @var EmailableInterface $notifiable */
        $notifiable = $notification->getNotifiable();
        if (!$notifiable instanceof EmailableInterface) {
            $this->createFormatterException(EmailableInterface::class, self::CHANNEL);
        }

        // Build the dispatch data array.
        $dispatchData = [
            'to' => $notifiable->getEmail(),
            'from' => empty($this->mailConfiguration['default_sender']) ? '' : $this->mailConfiguration['default_sender'],
        ];

        $messageData = self::createMessagaData($notification->getDataArray());

        return self::createMessage($dispatchData, $messageData, self::CHANNEL);
    }
}