<?php

namespace IrishDan\NotificationBundle\Test;

use IrishDan\NotificationBundle\DatabaseNotificationManager;

class DatabaseNotificationManagerTest extends NotificationTestCase
{
    protected $databaseNotificationManager;
    protected $entityManager;
    protected $managerRegistry;

    public function setUp()
    {
        $this->managerRegistry = $this->getService('doctrine');
        $this->databaseNotificationManager = new DatabaseNotificationManager($this->managerRegistry, [
            'entity' => 'Notification:Test\Notification',
        ]);
    }

    public function testCreateDatabaseNotification()
    {
        $data = [
            'title' => 'Test title',
            'body' => 'Test body',
            'uuid' => '12346789',
            'type' => 'AppBundle\Notification\TestNotification',
        ];

        // $databaseNotification = $this->databaseNotificationManager->createDatabaseNotification($data);
        // $this->assertFalse($databaseNotification);
    }
}