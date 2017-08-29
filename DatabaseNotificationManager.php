<?php

namespace IrishDan\NotificationBundle;

use IrishDan\NotificationBundle\Notification\DatabaseNotificationInterface;
use IrishDan\NotificationBundle\Notification\NotifiableInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class DatabaseNotificationManager
 *
 * @package IrishDan\NotificationBundle
 */
class DatabaseNotificationManager
{
    /**
     * @var array
     */
    protected $databaseConfiguration;
    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;
    /**
     * @var PropertyAccessor
     */
    protected $propertyAccessor;

    /**
     * DatabaseNotificationManager constructor.
     *
     * @param ManagerRegistry $managerRegistry
     * @param array           $databaseConfiguration
     */
    public function __construct(ManagerRegistry $managerRegistry, array $databaseConfiguration = [])
    {
        $this->managerRegistry = $managerRegistry;
        $this->databaseConfiguration = $databaseConfiguration;
    }

    /**
     * @return bool|\Doctrine\Common\Persistence\ObjectManager|null|object
     */
    protected function getEntityManager()
    {
        $entity = $this->notificationEntityName();
        if ($entity) {
            return $this->managerRegistry->getManagerForClass($entity);
        }

        return false;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function createDatabaseNotification(array $data)
    {
        if ($this->propertyAccessor === null) {
            $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        $entityManager = $this->getEntityManager();
        if ($entityManager) {
            $entity = $this->notificationEntityName();
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

    /**
     * @param DatabaseNotificationInterface $notification
     * @param null                          $now
     * @param bool                          $flush
     */
    public function setReadAtDate(DatabaseNotificationInterface $notification, $now = null, $flush = true)
    {
        if (empty($now)) {
            $now = new \DateTime();
        }

        $notification->setReadAt($now);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($notification);
        if ($flush) {
            $entityManager->flush();
        }
    }

    /**
     * @param NotifiableInterface $notifiable
     * @param null                $now
     */
    public function setUsersNotificationsAsRead(NotifiableInterface $notifiable, $now = null)
    {
        $entity = $this->notificationEntityName();
        if (!empty($entity)) {
            $options = [
                'notifiable' => $notifiable,
                'readAt' => null,
            ];

            $entityManager = $this->getEntityManager();
            $usersNotifications = $entityManager->getRepository($entity)->findBy($options);

            if (!empty($usersNotifications)) {
                foreach ($usersNotifications as $notification) {
                    $this->setNotificationsReadat($usersNotifications);
                }
            }
            $entityManager->flush();
        }
    }

    /**
     * @param array $notifications
     */
    public function setNotificationsReadAt(array $notifications)
    {
        $entityManager = $this->getEntityManager();
        foreach ($notifications as $notification) {
            $this->setReadAtDate($notification, null, false);
        }

        $entityManager->flush();
    }

    /**
     * @param DatabaseNotifiableInterface $user
     * @return array
     */
    public function getUsersUnreadNotifications(DatabaseNotifiableInterface $user)
    {
        $entityManager = $this->getEntityManager();

        if ($entityManager) {
            $entity = $this->notificationEntityName();
            $notifications = $entityManager->getRepository($entity)->getUnreadNotifications($user);

            return $notifications;
        }

        return [];
    }

    /**
     * @param NotifiableInterface $user
     * @param string              $status
     * @return int
     */
    public function getUsersNotificationCount(NotifiableInterface $user, $status = '')
    {
        $entityManager = $this->getEntityManager();
        if ($entityManager) {
            $entity = $this->notificationEntityName();
            $count = $entityManager->getRepository($entity)->getNotificationsCount($user, $status);

            return $count;
        }

        return 0;
    }

    /**
     * @param array $options
     * @throws \Exception
     */
    public function findAndSetAsRead(array $options)
    {
        $entityManager = $this->getEntityManager();
        $entity = $this->notificationEntityName();
        $notifications = $entityManager->getRepository($entity)->findBy($options);

        try {
            $this->setNotificationsReadAt($notifications);
        } catch (\Exception $e) {
            throw new \Exception(
                $e->getMessage()
            );
        }
    }

    /**
     * @return bool|mixed
     */
    protected function notificationEntityName()
    {
        $config = $this->databaseConfiguration;
        if (!empty($config['entity'])) {
            return $config['entity'];
        }

        return false;
    }
}
