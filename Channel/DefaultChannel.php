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
class DefaultChannel extends BaseChannel implements ChannelInterface
{
    public function formatAndDispatch(NotificationInterface $notification)
    {
        $message = $this->format($notification);

        return $this->dispatch($message);
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
            return $this->dispatcher->dispatch($message);
        } catch (\Exception $e) {
            throw new MessageDispatchException(
                $e->getMessage() . ' ' . $e->getCode() . ' ' . $e->getFile() . ' ' . $e->getLine()
            );
        }
    }
}
