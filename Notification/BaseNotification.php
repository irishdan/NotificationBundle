<?php

namespace IrishDan\NotificationBundle\Notification;

abstract class BaseNotification implements NotificationInterface
{
    // @TODO: Currently in the interface
    abstract function getNotifiable();

    abstract public function getChannels();

    abstract public function getNotificationArray();

    abstract public function setNotifiable(NotifiableInterface $notifiable);

    // abstract public function toArray(NotifiableInterface $notifiable);
//
    // abstract public function via(NotifiableInterface $notifiable);
//
    // abstract public function toMail(NotifiableInterface $user);
}