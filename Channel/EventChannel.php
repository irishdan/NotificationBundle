<?php

namespace IrishDan\NotificationBundle\Channel;

use IrishDan\NotificationBundle\Adapter\MessageAdapterInterface;
use IrishDan\NotificationBundle\Dispatcher\MessageDispatcherInterface;
use IrishDan\NotificationBundle\Event\MessageCreatedEvent;
use IrishDan\NotificationBundle\Event\MessageDispatchedEvent;
use IrishDan\NotificationBundle\Exception\MessageDispatchException;
use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class DirectChannel
 *
 * @package NotificationBundle\Channel
 */
class EventChannel extends BaseChannel implements ChannelInterface
{
    private $adapters = [];
    private $eventDispatcher;

    public function formatAndDispatch(NotificationInterface $notification)
    {
        return false;
    }

    public function setAdapters($key, MessageAdapterInterface $adapter, array $config)
    {
        $adapter->setChannelName($key);
        $adapter->setConfiguration($config);

        $this->adapters[$key] = $adapter;
    }

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct();
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
            if (!empty($this->adapters[$dispatcherKey])) {
                $this->adapters[$dispatcherKey]->dispatch($message);

                // Dispatch the message event
                $messageEvent = new MessageDispatchedEvent($message);
                $this->eventDispatcher->dispatch(MessageDispatchedEvent::NAME, $messageEvent);
            } else {
                throw new MessageDispatchException(
                    sprintf('No adapter available with key "%s"', $dispatcherKey)
                );
            }
        } catch (\Exception $exception) {
            throw new MessageDispatchException($exception->getMessage());
        }
    }
}
