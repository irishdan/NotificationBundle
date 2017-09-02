<?php

namespace IrishDan\NotificationBundle\Test\Channel;

use IrishDan\NotificationBundle\Channel\EventChannel;
use IrishDan\NotificationBundle\Exception\MessageDispatchException;
use IrishDan\NotificationBundle\Test\Adapter\DummyAdapter;
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

    public function testDispatch()
    {
        $this->eventDispatcher->expects($this->once())->method('dispatch');

        $adapter = $this->getMockAdapter(false, true);
        $this->eventChannel->setAdapters('default', $adapter, []);

        $message = $this->getTestMessage();
        $message->setChannel('default');

        $this->eventChannel->dispatch($message);
    }

    public function testDispatchWithWrongChannelKey()
    {
        $dispatcher = $this->getMockAdapter();
        $this->eventChannel->setAdapters('default', $dispatcher, []);

        $message = $this->getTestMessage();
        $message->setChannel('not-default');

        $this->setExpectedException(MessageDispatchException::class);

        $this->eventChannel->dispatch($message);
    }

    public function testChannelDataIsPassedToAdapter()
    {
        $adapter = new DummyAdapter('test_channel', ['a' => 1]);

        $this->eventChannel->setAdapters('test_channel', $adapter, ['a' => 1]);

        $this->assertEquals('test_channel', $adapter->getChannelName());
        $configuration = ['a' => 1];
        $this->assertEquals($configuration, $adapter->getConfiguration());
    }
}
