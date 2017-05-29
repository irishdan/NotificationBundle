<?php

namespace NotificationBundle\Dispatcher;

use Doctrine\ORM\EntityManager;
use NotificationBundle\Message\BaseMessage;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DatabaseMessageDispatcher implements MessageDispatcherInterface
{
    protected $entityManager;
    protected $configuration;
    protected $accessor;

    public function __construct(EntityManager $entityManager, $configuration = [])
    {
        $this->entityManager = $entityManager;
        $this->configuration = $configuration;
    }

    public function dispatch(BaseMessage $message)
    {
        // Create the database notification entity
        $databaseNotification = $this->createDatabaseNotificationEntity($message);
        if ($databaseNotification) {
            // Save the notification to the database
            $this->entityManager->persist($databaseNotification);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }

    protected function createDatabaseNotificationEntity($data)
    {
        if ($this->accessor === null) {
            $this->accessor = PropertyAccess::createPropertyAccessor();
        }

        $entity = $this->configuration['entity'];
        if (!empty($entity)) {
            $em = $this->entityManager;
            $class = $em->getRepository($entity)->getClassName();
            $object = new $class();

            // Transfer values from message to databaseNotification.
            $properties = ['notifiable', 'uuid', 'type', 'data'];
            foreach ($properties as $property) {
                $value = $this->accessor->getValue($data, $property);
                $this->accessor->setValue($object, $property, $value);
            }

            return $object;
        }

        return false;
    }
}