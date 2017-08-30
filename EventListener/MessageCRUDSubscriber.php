<?php

namespace IrishDan\NotificationBundle\EventListener;

use IrishDan\NotificationBundle\Channel\EventChannel;
use IrishDan\NotificationBundle\Event\MessageCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MessageCRUDSubscriber implements EventSubscriberInterface
{
    private $eventChannel;

    public function __construct(EventChannel $eventChannel)
    {
        $this->eventChannel = $eventChannel;
    }

    public function onMessageCreated(MessageCreatedEvent $event)
    {
        $message = $event->getMessage();
        $this->eventChannel->dispatch($message);
    }

    public static function getSubscribedEvents()
    {
        return [
            MessageCreatedEvent::NAME => 'onMessageCreated',
        ];
    }
}
