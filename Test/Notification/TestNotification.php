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

    protected $dataArray = [
        'title' => 'New member',
        'body' => 'A new member has just joined',
    ];

    public function getDataArray()
    {
        return $this->dataArray;
    }

    public function setDataArray(array $data)
    {
        $this->dataArray = $data;
    }

    public function getTemplateArray()
    {
        // The view template to use for this message. can switch depending on the channel.
        return [
            'title' => 'title.message.html.twig',
            'body' => $this->channel . '.message.html.twig',
        ];
    }
}