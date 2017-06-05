<?php

namespace IrishDan\NotificationBundle\Dispatcher;

use IrishDan\NotificationBundle\Message\BaseMessage;
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

    public function dispatch(BaseMessage $data)
    {
        if (empty($this->client)) {
            $credentials = new Client\Credentials\Basic($this->nexmoConfiguration['api_key'], $this->nexmoConfiguration['api_secret']);
            $this->client = new Client($credentials);
        }

        $message = $this->client->message()->send([
            'to' => $data->getTo(),
            'from' => $this->nexmoConfiguration['from'],
            'text' => $data->getData()['body'],
        ]);
    }
}