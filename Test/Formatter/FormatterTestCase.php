<?php

namespace IrishDan\NotificationBundle\Test\Formatter;

use IrishDan\NotificationBundle\Test\NotificationTestCase;

abstract class FormatterTestCase extends NotificationTestCase
{
    protected $notification;

    public function setUp()
    {
        parent::setUp();

        $this->setTwigTemplatesDirectory();

        $this->notification = $this->getNotificationWithUser();
    }

    protected function setTwigTemplatesDirectory()
    {
        $path = __DIR__ . '/../Resources/';
        $this->getService('twig.loader')->addPath($path, $namespace = '__main__');
    }
}