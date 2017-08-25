<?php

namespace IrishDan\NotificationBundle\Dispatcher;

use Doctrine\ORM\EntityManager;
use IrishDan\NotificationBundle\Message\BaseMessage;
use IrishDan\NotificationBundle\Message\MessageInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DatabaseMessageDispatcher implements MessageDispatcherInterface
{
    protected $entityManager;
    protected $managerRegistry;
    protected $configuration;
    protected $accessor;

    public function __construct(ManagerRegistry $managerRegistry, $configuration = [])
    {
        // $this->entityManager = $entityManager;
        $this->managerRegistry = $managerRegistry;
        $this->configuration = $configuration;
    }

    public function dispatch(MessageInterface $message)
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

    protected function createDatabaseNotificationEntity($message)
    {
        if ($this->accessor === null) {
            $this->accessor = PropertyAccess::createPropertyAccessor();
        }

        // Get the dispatch and message data from the message.
        $dispatchData = $message->getDispatchData();
        $messageData = $message->getMessageData();
        $data = $dispatchData + $messageData;

        $entity = $this->configuration['entity'];
        if (!empty($entity)) {
            $this->entityManager = $this->managerRegistry->getManagerForClass($entity);
            $class = $this->entityManager->getRepository($entity)->getClassName();
            $object = new $class();

            // Transfer values from message to databaseNotification.
            $properties = ['notifiable', 'uuid', 'type', 'body', 'title'];
            foreach ($properties as $property) {
                $value = $this->accessor->getValue($data, '[' . $property . ']');
                $this->accessor->setValue($object, $property, $value);
            }

            return $object;
        }

        return false;
    }
}