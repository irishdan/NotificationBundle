<?php

namespace IrishDan\NotificationBundle\Channel;

use IrishDan\NotificationBundle\Dispatcher\MessageDispatcherInterface;
use IrishDan\NotificationBundle\Event\MessageCreatedEvent;
use IrishDan\NotificationBundle\Event\MessageDispatchedEvent;
use IrishDan\NotificationBundle\Exception\MessageDispatchException;
use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class DefaultChannel
 *
 * @package NotificationBundle\Channel
 */
class EventChannel extends BaseChannel implements ChannelInterface
{
    private $dispatchers = [];
    private $eventDispatcher;

    public function formatAndDispatch(NotificationInterface $notification)
    {
        $this->format($notification);
    }

    public function setDispatchers($key, MessageDispatcherInterface $dispatcher)
    {
        $this->dispatchers[$key] = $dispatcher;
    }

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function dispatchFromEvent(MessageCreatedEvent $event)
    {
        $message = $event->getMessage();
        $this->dispatch($message);
    }

    public function dispatch(MessageInterface $message)
    {
        $dispatcherKey = $message->getChannel();

        // Dispatch the message
        try {
            if (!empty($this->dispatchers[$dispatcherKey])) {
                $this->dispatchers[$dispatcherKey]->dispatch($message);
            } else {
                throw new MessageDispatchException(
                    sprintf('No dispatcher available with key "%s"', $dispatcherKey)
                );
            }
        } catch (\Exception $exception) {
            throw new MessageDispatchException($exception->getMessage());
        }

        // Dispatch the message event
        $messageEvent = new MessageDispatchedEvent($message);
        $this->eventDispatcher->dispatch(MessageDispatchedEvent::NAME, $messageEvent);
    }
}
