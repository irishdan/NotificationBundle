<?php

namespace IrishDan\NotificationBundle\Test\Entity;

use IrishDan\NotificationBundle\DatabaseNotifiableInterface;
use IrishDan\NotificationBundle\EmailableInterface;
use IrishDan\NotificationBundle\Notification\NotifiableInterface;
use IrishDan\NotificationBundle\PusherableInterface;
use IrishDan\NotificationBundle\SlackableInterface;
use IrishDan\NotificationBundle\TextableInterface;

class User implements NotifiableInterface, EmailableInterface, TextableInterface, PusherableInterface, SlackableInterface, DatabaseNotifiableInterface
{
    private $id = 1;
    private $username = 'jimBob';
    private $email = 'jim@jim.bob';
    private $subscribedChannels = [
        'database',
        'mail',
        'pusher',
        'nexmo',
        'slack',
    ];

    public function getNumber()
    {
        return '+44755667788';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function isSubscribedToChannel($channel)
    {
        $subscribedChannels = $this->getSubscribedChannels();

        return in_array($channel, $subscribedChannels);
    }

    public function getSubscribedChannels()
    {
        return $this->subscribedChannels;
    }

    public function getPusherChannelSuffix()
    {
        return $this->id;
    }

    public function getSlackWebhook()
    {
        return 'https://hooks.slack.com/services/salty/salt/1234567890';
    }

    public function getIdentifier()
    {
        return $this->getId();
    }

    public function setSubscribedChannels(array $channels)
    {
        return $this->subscribedChannels = $channels;
    }
}
