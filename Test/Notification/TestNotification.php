<?php

namespace IrishDan\NotificationBundle\Test\Notification;

use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\Notification\NotifiableInterface;

class TestNotification implements NotificationInterface
{
    protected $notifiable;
    protected $channel;
    protected $uuid;

    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    public function getNotifiable()
    {
        return $this->notifiable;
    }

    public function setNotifiable(NotifiableInterface $notifiable)
    {
        $this->notifiable = $notifiable;
    }

    public function getChannels()
    {
        return ['mail', 'database', 'pusher', 'nexmo', 'slack'];
    }

    public function getDataArray()
    {
        $messageData = [
            'title' => 'New member',
            'body'  => 'A new member has just joined',
        ];

        // switch ($this->channel) {
        //     case 'database':
        //         $messageData['database specific data']
        //         break;
        // case 'pusher':
        //         $messageData['icon'] = 'pusher-icon-class'
        //         break;
        // }

        return $messageData;
    }

    public function getTemplate()
    {
        // The view template to use for this message. can switch depending on the channel.
        return 'NotificationBundle:Test:' . $this->channel . '.message.html.twig';
    }
}