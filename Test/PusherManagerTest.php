<?php

namespace IrishDan\NotificationBundle\Test;

use IrishDan\NotificationBundle\PusherManager;

class PusherManagerTest extends NotificationTestCase
{
    protected $pusherManager;

    public function setUp()
    {
        $config = $this->getNotificationChannelConfiguration('pusher');
        $this->pusherManager = new PusherManager();
        $this->pusherManager->setConfig($config);
    }

    public function testGetPusherClient()
    {
        $client = $this->pusherManager->getPusherClient();

        $this->assertInstanceOf(\Pusher::class, $client);
    }

    public function testGetUserChannelName()
    {
        $user = $this->getTestUser();

        $channelName = $this->pusherManager->getUserChannelName($user);

        $this->assertEquals('pusher_test_1', $channelName);
    }
}