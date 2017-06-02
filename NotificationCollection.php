<?php

namespace IrishDan\NotificationBundle;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class NotificationCollection
 *
 * @package NotificationBundle\Utils
 */
class NotificationCollection
{
    protected $entities;

    public function __construct(array $objects)
    {
        $this->entities = new ArrayCollection();

        foreach ($objects as $entity) {
            $this->getNotifications()->add($entity);
        }
    }

    public function getNotifications()
    {
        return $this->entities;
    }

    /**
     * Mark all notification as read.
     *
     * @return void
     */
    public function markAsRead()
    {
        // @TODO: sort it.
        $this->entities->forAll(function ($notification) {
            $notification->markAsRead();
        });
    }
}