<?php

namespace IrishDan\NotificationBundle\Test;

use IrishDan\NotificationBundle\ChannelManager;
use IrishDan\NotificationBundle\DatabaseNotificationManager;
use IrishDan\NotificationBundle\NotificationManager;
use IrishDan\NotificationBundle\Test\Notification\TestNotification;

class NotificationManagerTest extends NotificationTestCase
{
    protected $manager;
    protected $channelManager;
    protected $databaseManager;
    protected $notification;

    public function setUp()
    {
        parent::setUp();

        $this->channelManager = $this->getMockBuilder(ChannelManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->databaseManager = $this->getMockBuilder(DatabaseNotificationManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->manager = new NotificationManager($this->channelManager);

        $this->notification = new TestNotification();
    }

    public function testSend()
    {
        $recipient = $this->getTestUser();

        $this->channelManager->expects($this->once())
            ->method('send');

        $this->manager->send($this->notification, $recipient);
    }

    public function testSendMultiple()
    {
        $recipient = $this->getTestUser();

        $this->channelManager->expects($this->any())
            ->method('send');

        $this->manager->send($this->notification, [$recipient, $recipient]);
    }

    public function testSendWitData()
    {
        $recipient = $this->getTestUser();

        $this->channelManager->expects($this->any())
            ->method('send');

        $this->manager->send($this->notification, [$recipient, $recipient], ['extra_data' => 'test_data']);

        $data = $this->notification->getDataArray();

        $this->assertArrayHasKey('body', $data);
        $this->assertArrayHasKey('title', $data);
        $this->assertArrayHasKey('extra_data', $data);
        $this->assertEquals('test_data', $data['extra_data']);
    }

    public function testMarkAsRead()
    {
    }

    public function testMarkAllAsRead()
    {

    }

    public function testAllNotificationCount()
    {
    }

    public function testUnreadNotificationCount()
    {
    }

    public function testReadNotificationCount(NotifiableInterface $user)
    {
    }

    public function testNotificationCount()
    {
    }
}