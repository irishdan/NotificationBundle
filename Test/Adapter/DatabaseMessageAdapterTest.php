<?php

namespace IrishDan\NotificationBundle\Test\Adapter;

use IrishDan\NotificationBundle\Adapter\DatabaseMessageAdapter;
use IrishDan\NotificationBundle\DatabaseNotificationManager;
use IrishDan\NotificationBundle\Message\MessageInterface;


class DatabaseMessageAdapterTest extends AdapterTestCase
{
    protected $databaseManager;

    public function setUp()
    {
        parent::setUp();
        // Mock the DB manager
        $this->databaseManager = $this->getMockBuilder(DatabaseNotificationManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->adapter = new DatabaseMessageAdapter($this->databaseManager);
        $this->adapter->setChannelName('database');
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
Sent via database channel.';

        $this->assertEquals($message, $messageData['body']);
    }

    public function assertValidDispatchData(MessageInterface $message)
    {
        $this->assertEquals('database', $message->getChannel());

        $dispatchData = $message->getDispatchData();
        $this->assertEquals(1, $dispatchData['id']);
        $this->assertArrayHasKey('uuid', $dispatchData);
        $this->assertArrayHasKey('notifiable', $dispatchData);
        $this->assertArrayHasKey('type', $dispatchData);
    }
}