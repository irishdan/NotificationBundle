<?php

namespace IrishDan\NotificationBundle\Repository;


use IrishDan\NotificationBundle\DatabaseNotifiableInterface;

interface DatabaseNotificationRepositoryInterface
{
    public function getNotificationsCount(DatabaseNotifiableInterface $user, $status = '');

    public function getUnreadNotifications(DatabaseNotifiableInterface $user);
}