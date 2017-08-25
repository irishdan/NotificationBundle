<?php

namespace IrishDan\NotificationBundle\Test;

use IrishDan\NotificationBundle\PusherManager;

class PusherManagerTest extends NotificationTestCase
{
    protected $pusherManager;

    public function setUp()
    {
        $this->pusherManager = new PusherManager(
            [
                'cluster' => 'cluster',
                'encyrpted' => true,
                'auth_key' => 123456,
                'secret' => 'abcdef',
                'app_id' => 'oioi123',
                'channel_name' => 'test_channel_',
            ]
        );
    }

    public function testGetPusherClient()
    {
        $client = $this->pusherManager->getPusherClient();

        $this->assertInstanceOf(\Pusher::class, $client);
    }

    public function testGetUserChannelName()
    {
        $user = $this->getTestUser();

        $channelName = $this->pusherManager->getUserChancd nelName($user);

        $this->assertEquals('test_channel_1', $channelName);
    }
}