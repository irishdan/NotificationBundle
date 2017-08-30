<?php

namespace IrishDan\NotificationBundle\Dispatcher;

use IrishDan\NotificationBundle\Message\MessageInterface;

class SlackWebhookMessageDispatcher implements MessageDispatcherInterface
{
    public function dispatch(MessageInterface $message)
    {
        // Get the dispatch and message data from the message.
        $dispatchData = $message->getDispatchData();
        $messageData = $message->getMessageData();

        // Build payload from the message data.
        $messageData['text'] = $messageData['body'];
        unset($messageData['body']);

        $data = 'payload=' . json_encode($messageData);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $dispatchData['webhook']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        if ($result !== 'ok') {
            return false;
        }

        curl_close($ch);

        return true;
    }
}