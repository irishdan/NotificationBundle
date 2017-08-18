<?php

namespace IrishDan\NotificationBundle\Dispatcher;

use IrishDan\NotificationBundle\Message\MessageInterface;
use Nexmo\Client;

class SlackWebhookMessageDispatcher implements MessageDispatcherInterface
{
    public function dispatch(MessageInterface $message)
    {
        // Get the dispatch and message data from the message.
        $dispatchData = $message->getDispatchData();
        $messageData  = $message->getMessageData();

        $msg = $messageData['body'];
        $url = $dispatchData['webhook'];

        // @TODO: Build from message data.
        $payload = 'payload={"text": "' . $msg . '", "icon_emoji": ":ghost:"}';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true); //set how many paramaters to post
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        curl_exec($ch); //execute and get the results
        curl_close($ch);
    }
}