<?php

namespace IrishDan\NotificationBundle\Channel;

use IrishDan\NotificationBundle\Dispatcher\MessageDispatcherInterface;
use IrishDan\NotificationBundle\Exception\MessageDispatchException;
use IrishDan\NotificationBundle\Exception\MessageFormatException;
use IrishDan\NotificationBundle\Formatter\MessageFormatterInterface;
use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;


/**
 * Class DefaultChannel
 *
 * @package NotificationBundle\Channel
 */
class DefaultChannel implements ChannelInterface
{
    private $formatter;
    private $dispatcher;

    public function setDispatcher(MessageDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function setDataFormatter(MessageFormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function __construct($configured = false, $channel = 'default')
    {
        $this->configured = $configured;
        $this->channel    = $channel;
    }

    public function format(NotificationInterface $notification)
    {
        try {
            // Do the formatting.
            $message = $this->formatter->format($notification);
            var_dump($message);

            return $message;
        } catch (\Exception $e) {
            throw new MessageFormatException(
                $e->getMessage() . ' ' . $e->getCode() . ' ' . $e->getFile() . ' ' . $e->getLine()
            );
        }

        // Dispatch the message
        // $this->dispatch($message);
    }

    public function dispatch(MessageInterface $message)
    {
        // Dispatch the message
        try {
            return $this->dispatcher->dispatch($message);
        } catch (\Exception $e) {
            throw new MessageDispatchException(
                $e->getMessage() . ' ' . $e->getCode() . ' ' . $e->getFile() . ' ' . $e->getLine()
            );
        }
    }
}
