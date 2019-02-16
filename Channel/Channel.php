<?php

namespace IrishDan\NotificationBundle\Channel;

use IrishDan\NotificationBundle\Event\MessageCreatedEvent;
use IrishDan\NotificationBundle\Event\MessageDispatchedEvent;
use IrishDan\NotificationBundle\Event\NotificationReadyToFormatEvent;
use IrishDan\NotificationBundle\Exception\MessageDispatchException;
use IrishDan\NotificationBundle\Exception\MessageFormatException;
use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


/**
 * Class Channel
 *
 * @package NotificationBundle\Channel
 */
class Channel extends BaseChannel implements ChannelInterface
{
    /**
     * @var
     */
    protected $eventDispatcher;
    protected $formatToEvent = false;
    protected $dispatchToEvent = false;

    /**
     * @param $dispatchToEvent
     */
    public function setDispatchAsEvent(bool $dispatchToEvent): void
    {
        $this->dispatchToEvent = $dispatchToEvent;
    }

    /**
     * @param $dispatchToEvent
     */
    public function setFormatAsEvent(bool $formatToEvent): void
    {
        $this->formatToEvent = $formatToEvent;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function formatAndDispatch(NotificationInterface $notification, $dispatchReadyEvent = true)
    {
        // Channels can be configured to format and dispatch either directly..
        // or via events..
        // Using events allows for the hooking into the formatting and dispatching..
        // process, perhaps t offload these process to a queue worker.
        if ($this->dispatchToEvent && $dispatchReadyEvent) {
            $readyEvent = new NotificationReadyToFormatEvent($notification);
            $this->eventDispatcher->dispatch(NotificationReadyToFormatEvent::NAME, $readyEvent);

            return;
        }

        // Format the message..
        // Creates a message object for each recipient
        // If twig enabled will use twig to render the message
        $message = $this->format($notification);

        // Dispatch the message..
        // Sends the message to the destination..
        $this->dispatch($message);

        $message->setStatus('sent');

        return $message;
    }

    public function format(NotificationInterface $notification)
    {
        try {
            // Do the formatting.
            $message = $this->adapter->format($notification);

            if (!empty($this->eventDispatcher)) {
                $messageEvent = new MessageCreatedEvent($message);
                $this->eventDispatcher->dispatch(MessageCreatedEvent::NAME, $messageEvent);
            }

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
            $sent = $this->adapter->dispatch($message);

            if ($sent && !empty($this->eventDispatcher)) {
                $messageEvent = new MessageDispatchedEvent($message);
                $this->eventDispatcher->dispatch(MessageDispatchedEvent::NAME, $messageEvent);
            }

            return $sent;
        } catch (\Exception $e) {
            throw new MessageDispatchException(
                $e->getMessage() . ' ' . $e->getCode() . ' ' . $e->getFile() . ' ' . $e->getLine()
            );
        }
    }
}
