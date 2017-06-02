<?php

namespace IrishDan\NotificationBundle;

use Doctrine\ORM\EntityManager;
use IrishDan\NotificationBundle\Notification\DatabaseNotificationInterface;
use IrishDan\NotificationBundle\Notification\NotifiableInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

class DatabaseNotificationManager
{
    private $databaseConfiguration;
    private $entityManager;

    public function __construct(EntityManager $entityManager, array $databaseConfiguration = [])
    {
        $this->entityManager = $entityManager;
        $this->databaseConfiguration = $databaseConfiguration;
    }

    public function setReadAtDate(DatabaseNotificationInterface $notification, $now = null, $flush = true)
    {
        if (empty($now)) {
            $now = new \DateTime();
        }

        $notification->setReadAt($now);

        $this->entityManager->persist($notification);
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function setUsersNotificationsAsRead(NotifiableInterface $notifiable, $now = null)
    {
        $entity = $this->notificationEntityName();
        if (!empty($entity)) {
            $options = [
                'notifiable' => $notifiable,
                'readAt' => null,
            ];
            $usersNotifications = $this->entityManager->getRepository($entity)->findBy($options);

            if (!empty($usersNotifications)) {
                foreach ($usersNotifications as $notification) {
                    $this->setReadAtDate($notification, null, false);
                }
            }
            $this->entityManager->flush();
        }
    }

    public function getUsersUnreadNotificationCount(NotifiableInterface $user, $status = '')
    {
        $entity = $this->notificationEntityName();
        if (!empty($entity)) {
            $count = $this->entityManager->getRepository($entity)->getNotificationsCount($user, $status);

            dump('count: ' . $count);

            return $count;
        }

        return 0;
    }

    protected function notificationEntityName()
    {
        $config = $this->databaseConfiguration;
        if (!empty($config['entity'])) {
            return $config['entity'];
        }

        return false;
    }
}
