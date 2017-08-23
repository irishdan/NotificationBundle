<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Exception\MessageFormatException;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\Pusherable;
use IrishDan\NotificationBundle\PusherManager;

class PusherDataFormatter extends BaseFormatter implements MessageFormatterInterface
{
    const CHANNEL = 'pusher';
    protected $pusherConfiguration;
    protected $pusherManager;

    public function __construct(PusherManager $pusherManager)
    {
        $this->pusherManager = $pusherManager;
    }

    public function format(NotificationInterface $notification)
    {
        $notification->setChannel(self::CHANNEL);
        parent::format($notification);

        /** @var Pusherable $notifiable */
        $notifiable = $notification->getNotifiable();
        if (!$notifiable instanceof Pusherable) {
            throw new MessageFormatException(
                'Notifiable must implement Pusherable interface in order to format email message'
            );
        }

        // Build the dispatch data array.
        $dispatchData = [
            'channel' => $this->pusherManager->getUserChannelName($notifiable),
            'event'   => $this->pusherManager->getEvent(),
        ];

        $messageData = self::createMessagaData($notification->getDataArray());
        $message     = self::createMessage($dispatchData, $messageData, self::CHANNEL);

        return $message;
    }
}