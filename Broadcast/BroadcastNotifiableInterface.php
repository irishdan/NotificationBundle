<?php

namespace IrishDan\NotificationBundle\Broadcast;


interface BroadcastNotifiableInterface
{
    public function setSlackWebhook($webhook);

    public function getSlackWebhook();

    public function setPusherChannel($channelName);

    public function getPusherChannel();
}