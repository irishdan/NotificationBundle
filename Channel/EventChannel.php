<?php

namespace IrishDan\NotificationBundle\Channel;

use IrishDan\NotificationBundle\Dispatcher\MessageDispatcherInterface;
use IrishDan\NotificationBundle\Event\MessageCreatedEvent;
use IrishDan\NotificationBundle\Exception\MessageDispatchException;
use IrishDan\NotificationBundle\Exception\MessageFormatException;
use IrishDan\NotificationBundle\Formatter\MessageFormatterInterface;
use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class DefaultChannel
 *
 * @package NotificationBundle\Channel
 */
class EventChannel implements ChannelInterface
{
    private $formatter;
    private $dispatchers = [];
    private $eventDispatcher;

    public function setDispatchers($dispatcherKey, MessageDispatcherInterface $dispatcher)
    {
        $this->dispatchers[$dispatcherKey] = $dispatcher;
    }

    public function setDataFormatter(MessageFormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function __construct($configured = false, $channel = 'default', EventDispatcherInterface $eventDispatcher)
    {
        $this->configured      = $configured;
        $this->channel         = $channel;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function format(NotificationInterface $notification)
    {
        try {
            // Do the formatting.
            $message = $this->formatter->format($notification);
        } catch (\Exception $e) {
            throw new MessageFormatException();
        }

        // Dispatch the message event
        $messageEvent = new MessageCreatedEvent($message);
        $this->eventDispatcher->dispatch(MessageCreatedEvent::NAME, $messageEvent);
    }

    public function dispatchFromEvent(MessageCreatedEvent $event)
    {
        $message = $event->getMessage();
        $this->dispatch($message);
    }

    public function dispatch(MessageInterface $message)
    {
        // @TODO: need to figure out which channel message was supposed to be dispatched on.
        $dispatcherKey = $message->getChannel();

        // Dispatch the message
        try {

        } catch (\Exception $exception) {
            throw new MessageDispatchException();
        }
    }
}
