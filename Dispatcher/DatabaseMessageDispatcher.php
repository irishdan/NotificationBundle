<?php

namespace IrishDan\NotificationBundle\Dispatcher;

use Doctrine\ORM\EntityManager;
use IrishDan\NotificationBundle\Message\BaseMessage;
use IrishDan\NotificationBundle\Message\MessageInterface;
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

    public function dispatch(MessageInterface $message)
    {
        // Get the dispatch and message data from the message.
        $dispatchData = $message->getDispatchData();
        $messageData = $message->getMessageData();

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