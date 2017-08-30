<?php

namespace IrishDan\NotificationBundle\Test\Adapter;

use IrishDan\NotificationBundle\Adapter\NexmoMessageAdapter;
use IrishDan\NotificationBundle\Message\MessageInterface;

class NexmoMessageAdapterTest extends AdapterTestCase
{
    public function setUp()
    {
        parent::setUp();
        $parameters = $this->getParametersFromContainer('notification.channel.nexmo.configuration');
        $this->adapter = new NexmoMessageAdapter($parameters);
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
Sent via nexmo channel.';

        $this->assertEquals($message, $messageData['body']);
    }

    private function assertValidDispatchData(MessageInterface $message)
    {
        $this->assertEquals('nexmo', $message->getChannel());

        $dispatchData = $message->getDispatchData();
        $this->assertArrayHasKey('to', $dispatchData);
        $this->assertArrayHasKey('from', $dispatchData);

        $this->assertEquals('+44755667788', $dispatchData['to']);
        $this->assertEquals('JimBob', $dispatchData['from']);
    }
}