<?php

namespace IrishDan\NotificationBundle\Dispatcher;

use IrishDan\NotificationBundle\Message\MessageInterface;

/**
 * Interface MessageDispatcherInterface
 *
 * @package NotificationBundle\Dispatcher
 */
interface MessageDispatcherInterface
{
    /**
     * @param MessageInterface $message
     *
     * @return boolean
     */
    public function dispatch(MessageInterface $message);
}