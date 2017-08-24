<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\TextableInterface;

/**
 * Class NexmoDataFormatter
 *
 * @package IrishDan\NotificationBundle\Formatter
 */
class NexmoDataFormatter extends BaseFormatter implements MessageFormatterInterface
{
    const CHANNEL = 'nexmo';
    protected $nexmoConfiguration;

    public function __construct(array $nexmoConfiguration = [])
    {
        $this->nexmoConfiguration = $nexmoConfiguration;
    }

    /**
     * Generates a message object
     *
     * @param NotificationInterface $notification
     * @return \IrishDan\NotificationBundle\Message\Message
     */
    public function format(NotificationInterface $notification)
    {
        $notification->setChannel(self::CHANNEL);
        parent::format($notification);

        /** @var TextableInterface $notifiable */
        $notifiable = $notification->getNotifiable();
        if (!$notifiable instanceof TextableInterface) {
            $this->createFormatterException(TextableInterface::class, self::CHANNEL);
        }

        // Build the dispatch data array.
        $dispatchData = [
            'to' => $notifiable->getNumber(),
            'from' => empty($this->nexmoConfiguration['from']) ? '' : $this->nexmoConfiguration['from'],
        ];

        $messageData = self::createMessagaData($notification->getDataArray());

        return self::createMessage($dispatchData, $messageData, self::CHANNEL);
    }
}