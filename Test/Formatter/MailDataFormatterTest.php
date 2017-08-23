<?php

namespace IrishDan\NotificationBundle\Test\Formatter;


use IrishDan\NotificationBundle\Formatter\MailDataFormatter;
use IrishDan\NotificationBundle\Message\MessageInterface;

class MailDataFormatterTest extends FormatterTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->formatter = new MailDataFormatter(
            [
                'default_sender' => 'test@jim.bob',
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
        $this->assertEquals('Mail notification message for jimBob', $messageData['body']);
    }

    private function assertValidDispatchData(MessageInterface $message)
    {
        $this->assertEquals('mail', $message->getChannel());

        $dispatchData = $message->getDispatchData();
        $this->assertArrayHasKey('to', $dispatchData);
        $this->assertArrayHasKey('from', $dispatchData);

        $this->assertEquals('jim@jim.bob', $dispatchData['to']);
        $this->assertEquals('test@jim.bob', $dispatchData['from']);
    }
}