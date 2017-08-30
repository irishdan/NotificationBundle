<?php

namespace IrishDan\NotificationBundle\Channel;

use IrishDan\NotificationBundle\Event\MessageCreatedEvent;
use IrishDan\NotificationBundle\Event\MessageDispatchedEvent;
use IrishDan\NotificationBundle\Exception\MessageDispatchException;
use IrishDan\NotificationBundle\Exception\MessageFormatException;
use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


/**
 * Class DefaultChannel
 *
 * @package NotificationBundle\Channel
 */
class DefaultChannel extends BaseChannel implements ChannelInterface
{
    protected $eventDispatcher;
    protected $dispatchToEvent = true;

    public function setDispatchToEvent($dispatchToEvent)
    {
        $this->dispatchToEvent = $dispatchToEvent;
    }

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function formatAndDispatch(NotificationInterface $notification)
    {
        $message = $this->format($notification);

        if (!empty($this->eventDispatcher) && $this->dispatchToEvent) {
            $messageEvent = new MessageCreatedEvent($message);
            $this->eventDispatcher->dispatch(MessageCreatedEvent::NAME, $messageEvent);

            return true;
        } else {
            return $this->dispatch($message);
        }
    }

    public function format(NotificationInterface $notification)
    {
        try {
            // Do the formatting.
            $message = $this->formatter->format($notification);

            return $message;
        } catch (\Exception $e) {
            throw new MessageFormatException(
                $e->getMessage() . ' ' . $e->getCode() . ' ' . $e->getFile() . ' ' . $e->getLine()
            );
        }
    }

    public function dispatch(MessageInterface $message)
    {
        // Dispatch the message
        try {
            $sent = $this->dispatcher->dispatch($message);

            if ($sent && !empty($this->eventDispatcher)) {
                $event = new MessageDispatchedEvent($message);
                $this->eventDispatcher->dispatch(MessageDispatchedEvent::NAME, $event);
            }

            return $sent;
        } catch (\Exception $e) {
            throw new MessageDispatchException(
                $e->getMessage() . ' ' . $e->getCode() . ' ' . $e->getFile() . ' ' . $e->getLine()
            );
        }
    }
}
