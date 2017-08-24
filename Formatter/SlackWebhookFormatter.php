<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\SlackableInterface;

/**
 * Class SlackWebhookFormatter
 *
 * @package IrishDan\NotificationBundle\Formatter
 */
class SlackWebhookFormatter extends BaseFormatter implements MessageFormatterInterface
{
    const CHANNEL = 'slack';

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

        /** @var SlackableInterface $notifiable */
        $notifiable = $notification->getNotifiable();
        if (!$notifiable instanceof SlackableInterface) {
            $this->createFormatterException(SlackableInterface::class, self::CHANNEL);
        }

        // Build the dispatch data array.
        $dispatchData = [
            'webhook' => $notifiable->getSlackWebhook(),
        ];

        $messageData = self::createMessagaData($notification->getDataArray());

        return self::createMessage($dispatchData, $messageData, self::CHANNEL);
    }
}