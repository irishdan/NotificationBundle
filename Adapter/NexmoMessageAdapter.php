<?php

namespace IrishDan\NotificationBundle\Adapter;

use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\TextableInterface;
use Nexmo\Client;

class NexmoMessageAdapter extends BaseMessageAdapter implements MessageAdapterInterface
{
    const CHANNEL = 'nexmo';
    protected $nexmoConfiguration;
    protected $client;

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

    public function dispatch(MessageInterface $message)
    {
        // Get the dispatch and message data from the message.
        $dispatchData = $message->getDispatchData();
        $messageData = $message->getMessageData();

        if (empty($this->client)) {
            $credentials = new Client\Credentials\Basic(
                $this->nexmoConfiguration['api_key'],
                $this->nexmoConfiguration['api_secret']
            );
            $this->client = new Client($credentials);
        }

        $sent = $this->client->message()->send(
            [
                'to' => $dispatchData['to'],
                'from' => $dispatchData['from'],
                'text' => $messageData['body'],
            ]
        );

        return !empty($sent);
    }
}