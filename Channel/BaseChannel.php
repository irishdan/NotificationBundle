<?php

namespace NotificationBundle\Channel;

use NotificationBundle\Message\BaseMessage;
use NotificationBundle\Notification\NotificationInterface;
use NotificationBundle\Formatter\MessageFormatterInterface;
use NotificationBundle\Dispatcher\MessageDispatcherInterface;
use RuntimeException;

abstract class BaseChannel implements ChannelInterface
{
    protected $configured;
    protected $dataFormatter;
    protected $dispatcher;
    protected $channel;

    public function setDispatcher(MessageDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function setDataFormatter(MessageFormatterInterface $formatter)
    {
        $this->dataFormatter = $formatter;
    }

    protected function channelIsConfigured()
    {
        return $this->configured;
    }

    protected function format(NotificationInterface $notification)
    {
        if ($this->dataFormatter instanceof MessageFormatterInterface) {
            return $this->dataFormatter->format($notification);
        }

        return false;
    }

    protected function dispatch(BaseMessage $message)
    {
        if ($this->dispatcher instanceof MessageDispatcherInterface) {
            return $this->dispatcher->dispatch($message);
        }

        return false;
    }

    public function send(NotificationInterface $notification)
    {
        if ($this->channelIsConfigured()) {
            $data = $this->format($notification);
            if (!empty($data)) {
                try {
                    $this->dispatch($data);
                } catch (\Exception $exception) {

                    // Create a custom Exception message
                    throw new RuntimeException(
                        $this->exceptionMessage($exception)
                    );
                }
            }
        } else {
            throw new RuntimeException(
                'Channel not configured correctly'
            );
        }
    }

    protected function exceptionMessage(\Exception $exception)
    {
        return sprintf('Notification not sent: %s. File: %s. Line %s ', $exception->getMessage(), $exception->getFile(), $exception->getLine());
    }
}
