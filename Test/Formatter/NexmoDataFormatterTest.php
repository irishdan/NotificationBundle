<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Test\Formatter\FormatterTestCase;

class NexmoDataFormatterTest extends FormatterTestCase
{
    public function setUp()
    {
        parent::setUp();
        $parameters = $this->getParametersFromContainer('notification.channel.nexmo.configuration');
        $this->formatter = new NexmoDataFormatter($parameters);
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