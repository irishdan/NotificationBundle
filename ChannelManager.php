<?php

namespace IrishDan\NotificationBundle;

use InvalidArgumentException;
use IrishDan\NotificationBundle\Channel\ChannelInterface;
use IrishDan\NotificationBundle\Event\NotificationFailedEvent;
use IrishDan\NotificationBundle\Event\NotificationSentEvent;
use IrishDan\NotificationBundle\Notification\NotifiableInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;
use IrishDan\NotificationBundle\Event\NotificationSendingEvent;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ChannelManager
 *
 * @package NotificationBundle\Utils
 */
class ChannelManager
{
    /**
     * @var array
     */
    protected $channels = [];
    protected $eventDispatcher;
    /**
     * @var array
     */
    protected $configuredChannels;

    public function __construct(EventDispatcherInterface $eventDispatcher, array $configuredChannels)
    {
        $this->eventDispatcher    = $eventDispatcher;
        $this->configuredChannels = $configuredChannels;
    }

    /**
     * @param array $recipients
     *
     * @return array
     */
    protected function formatRecipients(array $recipients)
    {
        foreach ($recipients as $key => $recipient) {
            if (!$recipient instanceof NotifiableInterface) {
                unset($recipients[$key]);
            }
        }

        return $recipients;
    }

    /**
     * @param array                 $notifiables
     * @param NotificationInterface $notification
     */
    public function send(array $notifiables, NotificationInterface $notification)
    {
        $notifiables = $this->formatRecipients($notifiables);
        $this->sendNow($notifiables, $notification);
    }

    public function sendNow(array $recipients, NotificationInterface $notification)
    {
        // Clone the original notification as will need a copy for each recipient;
        $original = clone $notification;

        // Set a uuid so notifications from different channels can be grouped.
        // Needed when marking as read across all channels.
        $uuid = uniqid();

        foreach ($recipients as $notifiable) {
            // Get all of the channels the notification would like to be send on.
            // Then check each channel against what is configured in the system,
            // and which channels the notifiable is subscribed to.
            $viaChannels = $notification->getChannels();
            if (empty($viaChannels)) {
                $viaChannels = $this->configuredChannels;
            }

            foreach ($viaChannels as $channel) {
                if (!$this->shouldSendNotification($notifiable, $notification, $channel)) {
                    continue;
                }

                $currentNotification = clone $original;
                $this->formatNotification($notifiable, $currentNotification, $channel, $uuid);

                try {
                    // Dispatch sending event.
                    $sendingEvent = new NotificationSendingEvent($currentNotification);
                    $this->eventDispatcher->dispatch(NotificationSendingEvent::NAME, $sendingEvent);

                    $response = $this->channels[$channel]->formatAndDispatch($currentNotification);

                    if ($response) {
                        // Dispatch sent event.
                        $successEvent = new NotificationSentEvent($currentNotification);
                        $this->eventDispatcher->dispatch(NotificationSentEvent::NAME, $successEvent);
                    }
                } catch (\Exception $exception) {
                    // Dispatch sending failed event.
                    $successEvent = new NotificationFailedEvent($currentNotification);
                    $this->eventDispatcher->dispatch(NotificationFailedEvent::NAME, $successEvent);

                    throw $exception;
                }
            }
        }
    }

    /**
     * Based on the available channels (configured in system),
     * the notifiable's subscribed channels,
     * and the nofications
     * determines if the notification can be sent.
     *
     * @param  mixed  $notifiable
     * @param  mixed  $notification
     * @param  string $channel
     *
     * @return bool
     */
    protected function shouldSendNotification(NotifiableInterface $notifiable, NotificationInterface $notification, $channel)
    {
        $notifiableChannels = $notifiable->getSubscribedChannels();
        $configuredChannels = $notification->getChannels();

        if (
            in_array($channel, $configuredChannels)
            && in_array($channel, $notifiableChannels)
            && in_array($channel . '_channel', $this->configuredChannels)
            && in_array($channel, array_keys($this->channels))
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     * @param                       $channel
     */
    protected function formatNotification(NotifiableInterface $notifiable, NotificationInterface $notification, $channel, $uuid)
    {
        $notification->setNotifiable($notifiable);
        $notification->setChannel($channel);
        $notification->setUuid($uuid);
    }

    /**
     * Get a channel service
     *
     * @param  string|null $name
     *
     * @return mixed
     */
    public function getChannel($name = null)
    {
        return empty($this->channels[$name]) ? null : $this->channels[$name];
    }

    /**
     * @param                  $channelName
     * @param ChannelInterface $channel
     */
    public function setChannel($channelName, ChannelInterface $channel)
    {
        $this->channels[$channelName] = $channel;
    }
}
