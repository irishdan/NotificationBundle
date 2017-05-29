<?php

namespace NotificationBundle\Dispatcher;

use NotificationBundle\Message\BaseMessage;

/**
 * Interface MessageDispatcherInterface
 *
 * @package NotificationBundle\Dispatcher
 */
interface MessageDispatcherInterface
{
    /**
     * @param BaseMessage $message
     * @return mixed
     */
    public function dispatch(BaseMessage $message);
}