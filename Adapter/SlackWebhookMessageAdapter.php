<?php

namespace IrishDan\NotificationBundle\Adapter;

use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\SlackableInterface;

class SlackWebhookMessageAdapter extends BaseMessageAdapter implements MessageAdapterInterface
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