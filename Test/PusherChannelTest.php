<?php

namespace IrishDan\NotificationBundle\Test;


use IrishDan\NotificationBundle\PusherChannel;

class PusherChannelTest extends NotificationTestCase
{
    public function setUp()
    {
        $this->pusherChannel = new PusherChannel('test_channel', 'test_socket');
    }

    public function testGetChannelName()
    {
        $this->assertEquals('test_channel', $this->pusherChannel->getChannelName());
    }

    public function testGetSocketId()
    {
        $this->assertEquals('test_socket', $this->pusherChannel->getSocketId());
    }

    public function testSetChannelName()
    {
        $this->pusherChannel->setChannelName('test_channel_1');
        $this->assertEquals('test_channel_1', $this->pusherChannel->getChannelName());
    }

    public function testSetSocketId()
    {
        $this->pusherChannel->setSocketId('test_socket_1');
        $this->assertEquals('test_socket_1', $this->pusherChannel->getSocketId());
    }
}