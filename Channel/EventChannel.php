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
 * Class EventChannel
 *
 * This type of channel dispatched events when each Message
 * is created..
 * and when each Message is dispatched
 *
 * @package NotificationBundle\Channel
 */
class EventChannel extends BaseChannel implements ChannelInterface
{
    /**
     * An array of all available adapters.
     *
     * @var array
     */
    private $adapters = [];
    private $eventDispatcher;

    /**
     * @param                         $key
     * @param MessageAdapterInterface $adapter
     * @param array                   $config
     */
    public function setAdapters($key, MessageAdapterInterface $adapter, array $config)
    {
        // $adapter->setChannelName($key);
        $adapter->setConfiguration($config);

        $this->adapters[$key] = $adapter;
    }

    /**
     * @param NotificationInterface $notification
     * @return bool
     */
    public function formatAndDispatch(NotificationInterface $notification)
    {
        return false;
    }
}
