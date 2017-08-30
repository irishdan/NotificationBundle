<?php

namespace IrishDan\NotificationBundle\Test\Adapter;

use IrishDan\NotificationBundle\Adapter\SlackWebhookMessageAdapter;
use IrishDan\NotificationBundle\Message\MessageInterface;

class SlackWebhookAdapterTest extends AdapterTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->adapter = new SlackWebhookMessageAdapter();
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
Sent via slack channel.';

        $this->assertEquals($message, $messageData['body']);
    }

    public function assertValidDispatchData(MessageInterface $message)
    {
        $this->assertEquals('slack', $message->getChannel());

        $dispatchData = $message->getDispatchData();
        $this->assertEquals('https://hooks.slack.com/services/salty/salt/1234567890',
            $dispatchData['webhook']);
    }
}