<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Test\Formatter\FormatterTestCase;

class PusherDataFormatterTest extends FormatterTestCase
{
    public function setUp()
    {
        parent::setUp();

        $pusherManager = $this->getService('notification.pusher_manager');
        $this->formatter = new PusherDataFormatter($pusherManager);
    }

    public function testFormat()
    {
        $message = $this->formatter->format($this->notification);

        $this->assertValidDispatchData($message);
        $this->assertMessageDataStructure($message);

        $this->assertBasicMessageData($message);
    }

    public function testFormatWithTwig()
    {
        $this->setTwig();
        $message = $this->formatter->format($this->notification);

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