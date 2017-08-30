<?php

namespace IrishDan\NotificationBundle\EventListener;

use IrishDan\NotificationBundle\Channel\EventChannel;
use IrishDan\NotificationBundle\Event\MessageCreatedEvent;
use IrishDan\NotificationBundle\Event\MessageDispatchedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class MessageCRUDSubscriber
 *
 * @package IrishDan\NotificationBundle\EventListener
 */
class MessageCRUDSubscriber implements EventSubscriberInterface
{
    /**
     * @var EventChannel
     */
    protected $eventChannel;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * MessageCRUDSubscriber constructor.
     *
     * @param EventChannel $eventChannel
     */
    public function __construct(EventChannel $eventChannel, LoggerInterface $logger = null)
    {
        $this->eventChannel = $eventChannel;
        $this->logger = $logger;
    }

    /**
     * @param MessageCreatedEvent $event
     */
    public function onMessageCreated(MessageCreatedEvent $event)
    {
        $message = $event->getMessage();
        $this->eventChannel->dispatch($message);

        $this->log('Notification message "%s" created for "%s" via "%s" channel', $message);
    }

    public function onMessageDispatched(MessageDispatchedEvent $event)
    {
        $message = $event->getMessage();

        $this->log('Notification message "%s" dispatched to "%s" via "%s" channel', $message);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            MessageCreatedEvent::NAME => 'onMessageCreated',
            MessageDispatchedEvent::NAME => 'onMessageDispatched',
        ];
    }

    protected function log($string, $message)
    {
        if ($this->logger) {
            $log = sprintf($string, $message->getTitle(), $message->getRecipient(), $message->getChannel());
            $this->logger->info($log);
        }
    }
}
