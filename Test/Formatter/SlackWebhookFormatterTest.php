<?php

namespace IrishDan\NotificationBundle\Formatter;

use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Test\Formatter\FormatterTestCase;

class SlackWebhookFormatterTest extends FormatterTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->formatter = new SlackWebhookFormatter();
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
        $this->assertEquals('Slack notification message for jimBob', $messageData['body']);
    }

    public function assertValidDispatchData(MessageInterface $message)
    {
        $this->assertEquals('slack', $message->getChannel());

        $dispatchData = $message->getDispatchData();
        $this->assertEquals('https://hooks.slack.com/services/salty/salt/1234567890', $dispatchData['webhook']);
    }
}