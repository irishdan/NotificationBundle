<?php

namespace IrishDan\NotificationBundle\Test\Formatter;

use IrishDan\NotificationBundle\Test\NotificationTestCase;

abstract class FormatterTestCase extends NotificationTestCase
{
    protected $notification;

    public function setUp()
    {
        parent::setUp();

        $this->notification = $this->getNotificationWithUser();
    }
}