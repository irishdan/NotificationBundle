<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\PusherableInterface;
use IrishDan\NotificationBundle\PusherManager;

/**
 * Class PusherDataFormatter
 *
 * @package IrishDan\NotificationBundle\Formatter
 */
class PusherDataFormatter extends BaseFormatter implements MessageFormatterInterface
{
    const CHANNEL = 'pusher';
    protected $pusherConfiguration;
    protected $pusherManager;

    public function __construct(PusherManager $pusherManager)
    {
        $this->pusherManager = $pusherManager;
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

        /** @var PusherableInterface $notifiable */
        $notifiable = $notification->getNotifiable();
        if (!$notifiable instanceof PusherableInterface) {
            $this->createFormatterException(PusherableInterface::class, self::CHANNEL);
        }

        // Build the dispatch data array.
        $dispatchData = [
            'channel' => $this->pusherManager->getUserChannelName($notifiable),
            'event' => $this->pusherManager->getEvent(),
        ];

        $messageData = self::createMessagaData($notification->getDataArray());

        return self::createMessage($dispatchData, $messageData, self::CHANNEL);
    }
}