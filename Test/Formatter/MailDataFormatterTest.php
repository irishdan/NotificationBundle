<?php

namespace IrishDan\NotificationBundle\Test\Formatter;


use IrishDan\NotificationBundle\Formatter\MailDataFormatter;
use IrishDan\NotificationBundle\Message\MessageInterface;

class MailDataFormatterTest extends FormatterTestCase
{
    protected $formatter;

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

        $this->assertInstanceOf('IrishDan\NotificationBundle\Message\MessageInterface', $message);

        $this->assertValidDispatchData($message);

        $messageData = $message->getMessageData();
        $this->assertArrayHasKey('title', $messageData);
        $this->assertArrayHasKey('body', $messageData);

        $this->assertEquals('New member', $messageData['title']);
        $this->assertEquals('A new member has just joined', $messageData['body']);
    }

    public function testFormatWithTwig()
    {
        $twig = $this->getService('twig');
        $this->formatter->setTemplating($twig);
        $message = $this->formatter->format($this->notification);

        $this->assertValidDispatchData($message);

        // @TODO:
    }

    public function assertValidDispatchData(MessageInterface $message)
    {
        $this->assertEquals('mail', $message->getChannel());

        $dispatchData = $message->getDispatchData();
        $this->assertArrayHasKey('to', $dispatchData);
        $this->assertArrayHasKey('from', $dispatchData);

        $this->assertEquals('jim@jim.bob', $dispatchData['to']);
        $this->assertEquals('test@jim.bob', $dispatchData['from']);
    }
}