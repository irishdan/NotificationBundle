<?php

namespace IrishDan\NotificationBundle\Test\Entity;

use IrishDan\NotificationBundle\Emailable;
use IrishDan\NotificationBundle\Notification\NotifiableInterface;
use IrishDan\NotificationBundle\Pusherable;
use IrishDan\NotificationBundle\Slackable;
use IrishDan\NotificationBundle\Textable;

class User implements NotifiableInterface, Emailable, Textable, Pusherable, Slackable
{
    public function getNumber()
    {
        return '+44755667788';
    }

    private $id = 1;
    private $username = 'jimBob';
    private $email = 'jim@jim.bob';

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

    // Notifiable methods
    public function notifications()
    {
        // TODO: Implement notifications() method.
    }

    public function readNotifications()
    {
        // TODO: Implement readNotifications() method.
    }

    public function unreadNotifications()
    {
        // TODO: Implement unreadNotifications() method.
    }

    public function notify($instance)
    {
        // TODO: Implement notify() method.
    }

    public function getNotifiableDetailsForChannel($driver)
    {
        // TODO: Implement getNotifiableDetailsForChannel() method.
    }

    public function isSubscribedToChannel($channel)
    {
        $subscribedChannels = $this->getSubscribedChannels();

        return in_array($channel, $subscribedChannels);
    }

    public function getSubscribedChannels()
    {
        return [
            'database',
            'mail',
            'pusher',
            'nexmo',
            'slack',
        ];
    }

    public function getChannelSuffix()
    {
        return $this->id;
    }

    public function getSlackWebhook()
    {
        return 'https://hooks.slack.com/services/salty/salt/1234567890';
    }
}
