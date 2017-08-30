<?php

namespace IrishDan\NotificationBundle\Test\Channel;

use IrishDan\NotificationBundle\Channel\DirectChannel;
use IrishDan\NotificationBundle\Test\NotificationTestCase;

class DirectChannelTest extends NotificationTestCase
{
    public $DirectChannel;
    protected $notification;

    public function setUp()
    {
        parent::setUp();

        $this->notification = $this->getNotificationWithUser();
        $this->DirectChannel = new DirectChannel();
    }

    public function testFormat()
    {
        $adapter = $this->getMockAdapter(true);
        $this->DirectChannel->setAdapter($adapter);

        $message = $this->DirectChannel->format($this->notification);

        $this->assertInstanceOf('IrishDan\NotificationBundle\Message\MessageInterface', $message);
    }

    public function testDispatch()
    {
        $message = $this->getTestMessage();

        $adapter = $this->getMockAdapter(false, true);
        $this->DirectChannel->setAdapter($adapter);

        $dispatched = $this->DirectChannel->dispatch($message);

        $this->assertTrue($dispatched);
    }

    public function testFormatAndDispatch()
    {
        $formatter = $this->getMockAdapter(true, true);
        $this->DirectChannel->setAdapter($formatter);

        $dispatched = $this->DirectChannel->formatAndDispatch($this->notification);

        $this->assertTrue($dispatched);
    }
}
