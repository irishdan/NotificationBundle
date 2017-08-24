<?php

namespace IrishDan\NotificationBundle\Dispatcher;

use IrishDan\NotificationBundle\Message\MessageInterface;
use Nexmo\Client;

class NexmoMessageDispatcher implements MessageDispatcherInterface
{
    protected $nexmoConfiguration;
    /**
     * @var Client $client
     */
    protected $client;

    public function __construct(array $nexmoConfiguration)
    {
        $this->nexmoConfiguration = $nexmoConfiguration;
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