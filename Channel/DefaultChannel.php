<?php

namespace NotificationBundle\Channel;

/**
 * Class DefaultChannel
 *
 * @package NotificationBundle\Channel
 */
class DefaultChannel extends BaseChannel
{
    public function __construct($configured = false, $channel = 'default')
    {
        $this->configured = $configured;
        $this->channel = $channel;
    }
}
