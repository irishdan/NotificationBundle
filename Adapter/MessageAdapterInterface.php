<?php

namespace IrishDan\NotificationBundle\Adapter;


use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Notification\NotificationInterface;

interface MessageAdapterInterface
{
    /**
     * Takes a Notification, formats the content and delivery data
     * and returns a Message object which a dispatcher dispatch.
     *
     * @param NotificationInterface $notification
     * @return MessageInterface
     */
    public function format(NotificationInterface $notification);

    /**
     * @param MessageInterface $message
     *
     * @return boolean
     */
    public function dispatch(MessageInterface $message);
}