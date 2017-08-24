<?php

namespace IrishDan\NotificationBundle\Test\Formatter;


use IrishDan\NotificationBundle\Formatter\MailDataFormatter;
use IrishDan\NotificationBundle\Message\MessageInterface;

class MailDataFormatterTest extends FormatterTestCase
{
    public function setUp()
    {
        parent::setUp();

        $parameters = $this->getParametersFromContainer('notification.channel.mail.configuration');
        $this->formatter = new MailDataFormatter($parameters);
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
Sent via mail channel.';

        $this->assertEquals($message, $messageData['body']);
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