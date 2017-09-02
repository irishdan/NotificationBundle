<?php

namespace IrishDan\NotificationBundle\Adapter;

use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\PusherableInterface;
use IrishDan\NotificationBundle\PusherManager;

/**
 * Class PusherMessageAdapter
 *
 * @package IrishDan\NotificationBundle\Adapter
 */
class PusherMessageAdapter extends BaseMessageAdapter implements MessageAdapterInterface
{
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
        $this->pusherManager->setConfig($this->configuration);

        parent::format($notification);

        /** @var PusherableInterface $notifiable */
        $notifiable = $notification->getNotifiable();
        if (!$notifiable instanceof PusherableInterface) {
            $this->createFormatterException(PusherableInterface::class, $this->channelName);
        }

        // Build the dispatch data array.
        $dispatchData = [
            'channel' => $this->pusherManager->getUserChannelName($notifiable),
            'event' => $this->pusherManager->getEvent(),
        ];

        $messageData = self::createMessagaData($notification->getDataArray());

        return self::createMessage($dispatchData, $messageData, $this->channelName);
    }

    public function dispatch(MessageInterface $message)
    {
        // Get the dispatch and message data from the message.
        $dispatchData = $message->getDispatchData();
        $messageData = $message->getMessageData();

        $pusher = $this->pusherManager->getPusherClient();

        $channel = [
            $dispatchData['channel'],
        ];

        return !empty($pusher->trigger($channel, $dispatchData['event'], $messageData));
    }
}