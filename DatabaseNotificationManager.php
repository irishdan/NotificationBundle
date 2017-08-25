<?php

namespace IrishDan\NotificationBundle;

use IrishDan\NotificationBundle\Notification\DatabaseNotificationInterface;
use IrishDan\NotificationBundle\Notification\NotifiableInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DatabaseNotificationManager
{
    protected $databaseConfiguration;
    protected $managerRegistry;
    protected $propertyAccessor;

    public function __construct(ManagerRegistry $managerRegistry, array $databaseConfiguration = [])
    {
        $this->managerRegistry = $managerRegistry;
        $this->databaseConfiguration = $databaseConfiguration;
    }

    public function createDatabaseNotification(array $data)
    {
        if ($this->propertyAccessor === null) {
            $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        $entity = $this->notificationEntityName();
        if ($entity) {
            $entityManager = $this->managerRegistry->getManagerForClass($entity);
            $class = $entityManager->getRepository($entity)->getClassName();
            $databaseNotification = new $class();

            // Transfer values from message to databaseNotification.
            $properties = ['notifiable', 'uuid', 'type', 'body', 'title'];
            foreach ($properties as $property) {
                $value = $this->propertyAccessor->getValue($data, '[' . $property . ']');
                $this->propertyAccessor->setValue($databaseNotification, $property, $value);
            }

            // Save the notification to the database
            $entityManager->persist($databaseNotification);
            $entityManager->flush();

            return $databaseNotification;
        }

        return false;
    }

    public function setReadAtDate(DatabaseNotificationInterface $notification, $now = null, $flush = true)
    {
        if (empty($now)) {
            $now = new \DateTime();
        }

        $notification->setReadAt($now);

        $entityManager = $this->managerRegistry->getManagerForClass(get_class($notification));
        $entityManager->persist($notification);
        if ($flush) {
            $entityManager->flush();
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

            $entityManager = $this->managerRegistry->getManagerForClass($entity);
            $usersNotifications = $entityManager->getRepository($entity)->findBy($options);

            if (!empty($usersNotifications)) {
                foreach ($usersNotifications as $notification) {
                    $this->setReadAtDate($notification, null, false);
                }
            }
            $entityManager->flush();
        }
    }

    public function getUsersNotificationCount(NotifiableInterface $user, $status = '')
    {
        $entity = $this->notificationEntityName();
        if (!empty($entity)) {
            $entityManager = $this->managerRegistry->getManagerForClass($entity);
            // @TODO: Have a look at this.
            $count = $entityManager->getRepository($entity)->getNotificationsCount($user, $status);

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
