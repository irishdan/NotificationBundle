<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Test\Formatter\FormatterTestCase;

class NexmoDataFormatterTest extends FormatterTestCase
{
    public function setUp()
    {
        parent::setUp();

        // @TODO: use config for nexmo.
        $this->formatter = new NexmoDataFormatter(
            [
                'from' => 'JimBob',
            ]
        );
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
        $this->assertEquals('Nexmo notification message for jimBob', $messageData['body']);
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