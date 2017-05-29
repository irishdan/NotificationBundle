<?php

namespace NotificationBundle\Notification;

/**
 * Interface NotificationInterface
 *
 * @package NotificationBundle\Notification
 */
interface NotificationInterface
{
    /**
     * Get the notifiable entity that the notification belongs to.
     */
    public function getNotifiable();

    /**
     * Set the notifiable
     *
     * @param NotifiableInterface $notifiable
     */
    public function setNotifiable(NotifiableInterface $notifiable);

    /**
     * Returns an array of desired notification channel
     *
     * @return array
     */
    public function getChannels();

    /**
     * This is the channel that the notification  was sent via
     *
     * @param $channel
     * @return mixed
     */
    public function setChannel($channel);

    /**
     * @return mixed
     */
    public function getChannel();

    /**
     * @param $uuid
     * @return mixed
     */
    public function setUuid($uuid);

    /**
     * @return string
     */
    public function getUuid();

    /**
     * Returns an array of notification content array
     *
     * @return array
     */
    public function getDataArray();

    // @TODO: Explain, doc block
    public function getTemplate();
}