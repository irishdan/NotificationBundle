<?php

namespace IrishDan\NotificationBundle\Test\Adapter;

use IrishDan\NotificationBundle\Adapter\MailMessageAdapter;
use IrishDan\NotificationBundle\Message\MessageInterface;

class MailMessageAdapterTest extends AdapterTestCase
{
    public function setUp()
    {
        parent::setUp();

        $parameters = $this->getParametersFromContainer('notification.channel.mail.configuration');
        $mailer = $this->getMockBuilder(\Swift_mailer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->adapter = new MailMessageAdapter($mailer);
        $this->adapter->setConfiguration($parameters);
        $this->adapter->setChannelName('mail');
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