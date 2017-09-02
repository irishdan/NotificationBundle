<?php

namespace IrishDan\NotificationBundle\Test\Adapter;

use IrishDan\NotificationBundle\Adapter\PusherMessageAdapter;
use IrishDan\NotificationBundle\Message\MessageInterface;


class PusherMessageAdapterTest extends AdapterTestCase
{
    public function setUp()
    {
        parent::setUp();

        $pusherManager = $this->getService('notification.pusher_manager');
        $this->adapter = new PusherMessageAdapter($pusherManager);
        $this->adapter->setConfiguration([]);
        $this->adapter->setChannelName('pusher');
    }

    public function testFormat()
    {
        $message = $this->adapter->format($this->notification);

        $this->assertValidDispatchData($message);
        $this->assertMessageDataStructure($message);

        $this->assertBasicMessageData($message);
    }

    public function testFormatWithTwig()
    {
        $this->setTwig();
        $message = $this->adapter->format($this->notification);

        $this->assertValidDispatchData($message);
        $this->assertMessageDataStructure($message);

        $messageData = $message->getMessageData();
        $this->assertEquals('New member', $messageData['title']);
        $message = 'Hello jimBob
Notification message for jimBob
Sincerely yours,
NotificationBundle
Sent via pusher channel.';

        $this->assertEquals($message, $messageData['body']);
    }

    public function assertValidDispatchData(MessageInterface $message)
    {
        $this->assertEquals('pusher', $message->getChannel());

        $dispatchData = $message->getDispatchData();
        $this->assertArrayHasKey('channel', $dispatchData);

        $this->assertEquals('pusher_test_1', $dispatchData['channel']);
        $this->assertEquals('pusher_event', $dispatchData['event']);
    }
}