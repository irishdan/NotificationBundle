<?php

namespace IrishDan\NotificationBundle\Test\Channel;

use IrishDan\NotificationBundle\Channel\DefaultChannel;
use IrishDan\NotificationBundle\Test\NotificationTestCase;

class DefaultChannelTest extends NotificationTestCase
{
    public $defaultChannel;
    protected $notification;

    public function setUp()
    {
        parent::setUp();

        $this->notification   = $this->getNotificationWithUser();
        $this->defaultChannel = new DefaultChannel();
    }

    public function testFormat()
    {
        $formatter = $this->getMockFormatter(true);
        $this->defaultChannel->setDataFormatter($formatter);

        $message = $this->defaultChannel->format($this->notification);

        $this->assertInstanceOf('IrishDan\NotificationBundle\Message\MessageInterface', $message);
    }

    public function testDispatch()
    {
        $message = $this->getTestMessage();

        $dispatcher = $this->getMockDispatcher(true);
        $this->defaultChannel->setDispatcher($dispatcher);

        $dispatched = $this->defaultChannel->dispatch($message);

        $this->assertTrue($dispatched);
    }

    public function testFormatAndDispatch()
    {
        $formatter = $this->getMockFormatter(true);
        $this->defaultChannel->setDataFormatter($formatter);

        $dispatcher = $this->getMockDispatcher(true);
        $this->defaultChannel->setDispatcher($dispatcher);

        $dispatched = $this->defaultChannel->formatAndDispatch($this->notification);

        $this->assertTrue($dispatched);
    }
}
