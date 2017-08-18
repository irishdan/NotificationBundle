<?php

namespace IrishDan\NotificationBundle\Broadcast;


interface BroadcastNotifiableInterface
{
    public function setSlackWebhook($webhook);

    public function getSlackWebhook();
}