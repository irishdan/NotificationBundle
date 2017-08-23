<?php

namespace IrishDan\NotificationBundle\Test\Formatter;

use IrishDan\NotificationBundle\Message\MessageInterface;
use IrishDan\NotificationBundle\Test\NotificationTestCase;

abstract class FormatterTestCase extends NotificationTestCase
{
    protected $notification;
    protected $formatter;

    public function setUp()
    {
        parent::setUp();

        $this->setTwigTemplatesDirectory();

        $this->notification = $this->getNotificationWithUser();
    }

    protected function setTwig()
    {
        $twig = $this->getService('twig');
        $this->formatter->setTemplating($twig);
    }

    protected function setTwigTemplatesDirectory()
    {
        $path = __DIR__ . '/../Resources/';
        $this->getService('twig.loader')->addPath($path, $namespace = '__main__');
    }

    protected function assertMessageDataStructure(MessageInterface $message)
    {
        $this->assertInstanceOf('IrishDan\NotificationBundle\Message\MessageInterface', $message);

        $messageData = $message->getMessageData();
        $this->assertArrayHasKey('title', $messageData);
        $this->assertArrayHasKey('body', $messageData);
    }

    protected function assertBasicMessageData(MessageInterface $message)
    {
        $messageData = $message->getMessageData();
        $this->assertEquals('New member', $messageData['title']);
        $this->assertEquals('A new member has just joined', $messageData['body']);
    }
}