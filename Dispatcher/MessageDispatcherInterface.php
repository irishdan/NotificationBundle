<?php

namespace IrishDan\NotificationBundle\Dispatcher;

use IrishDan\NotificationBundle\Message\BaseMessage;

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