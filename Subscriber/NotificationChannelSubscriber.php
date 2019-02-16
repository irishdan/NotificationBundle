<?php

namespace IrishDan\NotificationBundle\Subscriber;

use IrishDan\NotificationBundle\Adapter\MessageAdapterInterface;
use IrishDan\NotificationBundle\Channel\ChannelInterface;
use IrishDan\NotificationBundle\Channel\EventChannel;
use IrishDan\NotificationBundle\Event\MessageCreatedEvent;
use IrishDan\NotificationBundle\Event\MessageDispatchedEvent;
use IrishDan\NotificationBundle\Event\NotificationReadyToFormatEvent;
use IrishDan\NotificationBundle\Exception\MessageDispatchException;
use IrishDan\NotificationBundle\Message\MessageInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class NotificationChannelSubscriber
 *
 * @package IrishDan\NotificationBundle\Subscriber
 */
class NotificationChannelSubscriber implements EventSubscriberInterface
{
    /**
     * An array of all available adapters.
     *
     * @var array
     */
    private $adapters = [];
    private $eventDispatcher;
    private $adapterlessChannel;
    private $channelConfigMap = [];

    public function __construct(EventDispatcherInterface $eventDispatcher, ChannelInterface $adapterlessChannel, array $channelConfigMap = [])
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->adapterlessChannel = $adapterlessChannel;
        $this->channelConfigMap = $channelConfigMap;
    }


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            NotificationReadyToFormatEvent::NAME => 'formatMessageFromEvent'
        ];
    }

    /**
     * @param                         $key
     * @param MessageAdapterInterface $adapter
     * @param array                   $config
     */
    public function addAdapter($key, MessageAdapterInterface $adapter, array $config = [])
    {
        $adapter->setChannelName($key);
        $this->adapters[$key] = $adapter;
    }


    public function formatMessageFromEvent(NotificationReadyToFormatEvent $event)
    {
        $notification = $event->getNotification();

        // get the adapter name from the
        // $adapterKey = $message->getChannel();
        $adapterKey = $this->channelConfigMap[$notification->getChannel()]['adapter'];
        $channelConfiguration =  $this->channelConfigMap[$notification->getChannel()]['config'];

        // Get the adapter for this channel
        // and inject it into the adapterless channel
        // disable dispatching to events also...
        if (!empty($this->adapters[$adapterKey])) {
            $adapter = $this->adapters[$adapterKey];

            // Below could be refactored into a an abstract class for use in a queue worker or else where
            $this->adapterlessChannel->setAdapter($adapter);
            $this->adapterlessChannel->setChannelConfiguration($channelConfiguration);

            $message = $this->adapterlessChannel->formatAndDispatch($notification, false);
            // @TODO: Event...

        } else {
            throw new MessageDispatchException(
                sprintf('No adapter available with key "%s"', $adapterKey)
            );
        }
    }
}
