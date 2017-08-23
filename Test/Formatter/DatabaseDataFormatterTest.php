<?php

namespace IrishDan\NotificationBundle\Test\Formatter;


use IrishDan\NotificationBundle\Formatter\DatabaseDataFormatter;
use IrishDan\NotificationBundle\Message\MessageInterface;

class DatabaseDataFormatterTest extends FormatterTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->formatter = new DatabaseDataFormatter();
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
        $this->assertEquals('Database notification message for jimBob', $messageData['body']);
    }

    public function assertValidDispatchData(MessageInterface $message)
    {
        $this->assertEquals('database', $message->getChannel());

        $dispatchData = $message->getDispatchData();
        $this->assertEquals(1, $dispatchData['id']);
    }
}