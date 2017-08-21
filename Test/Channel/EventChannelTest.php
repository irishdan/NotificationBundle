<?php

namespace IrishDan\NotificationBundle\Test\Channel;

use IrishDan\NotificationBundle\Channel\DefaultChannel;
use IrishDan\NotificationBundle\Channel\EventChannel;
use IrishDan\NotificationBundle\Exception\MessageDispatchException;
use IrishDan\NotificationBundle\Test\NotificationTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventChannelTest extends NotificationTestCase
{
    protected $eventChannel;
    protected $eventDispatcher;
    protected $notification;

    public function setUp()
    {
        parent::setUp();

        $this->notification = $this->getNotificationWithUser();

        $this->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
                                      ->disableOriginalConstructor()
                                      ->getMock();

        $this->eventChannel = new EventChannel($this->eventDispatcher);
    }

    public function testFormat()
    {
        $this->eventDispatcher->expects($this->once())->method('dispatch');

        $formatter = $this->getMockFormatter(true);
        $this->eventChannel->setDataFormatter($formatter);

        $message = $this->eventChannel->format($this->notification);

        $this->assertInstanceOf('IrishDan\NotificationBundle\Message\MessageInterface', $message);
    }

    public function testDispatch()
    {
        $this->eventDispatcher->expects($this->once())->method('dispatch');

        $dispatcher = $this->getMockDispatcher();
        $this->eventChannel->setDispatchers('default', $dispatcher);

        $message = $this->getTestMessage();
        $message->setChannel('default');

        $this->eventChannel->dispatch($message);
    }

    public function testDispatchWithWrongChannelKey()
    {
        $dispatcher = $this->getMockDispatcher();
        $this->eventChannel->setDispatchers('default', $dispatcher);

        $message = $this->getTestMessage();
        $message->setChannel('not-default');

        $this->setExpectedException(MessageDispatchException::class);

        $this->eventChannel->dispatch($message);
    }
}
