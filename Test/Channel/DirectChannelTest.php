<?php

namespace IrishDan\NotificationBundle\Test\Channel;

use IrishDan\NotificationBundle\Channel\Channel;
use IrishDan\NotificationBundle\Test\Adapter\DummyAdapter;
use IrishDan\NotificationBundle\Test\NotificationTestCase;

class ChannelTest extends NotificationTestCase
{
    protected $notification;

    public function getChannel($adapter)
    {
        $config = [
            'key' => '123abc',
            'id' => 123456,
        ];

        $channelName = 'test_channel';

        return new Channel($config, $channelName, $adapter);
    }

    public function setUp()
    {
        parent::setUp();

        $this->notification = $this->getNotificationWithUser();
    }

    public function testFormat()
    {
        $adapter = $this->getMockAdapter(true);
        $channel = $this->getChannel($adapter);

        $message = $channel->format($this->notification);

        $this->assertInstanceOf('IrishDan\NotificationBundle\Message\MessageInterface', $message);
    }

    public function testDispatch()
    {
        $message = $this->getTestMessage();

        $adapter = $this->getMockAdapter(false, true);
        $channel = $this->getChannel($adapter);

        $dispatched = $channel->dispatch($message);

        $this->assertTrue($dispatched);
    }

    public function testFormatAndDispatch()
    {
        $adapter = $this->getMockAdapter(true, true);
        $channel = $this->getChannel($adapter);

        $dispatched = $channel->formatAndDispatch($this->notification);

        $this->assertTrue($dispatched);
    }

    public function testChannelDataIsPassedToAdapter()
    {
        $adapter = new DummyAdapter();
        $this->getChannel($adapter);

        $this->assertEquals('test_channel', $adapter->getChannelName());
        $configuration = [
            'key' => '123abc',
            'id' => 123456,
        ];
        $this->assertEquals($configuration, $adapter->getConfiguration());
    }
}
