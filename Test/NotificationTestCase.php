<?php

namespace IrishDan\NotificationBundle\Test;

use IrishDan\NotificationBundle\Test\Notification\TestNotification;
use IrishDan\NotificationBundle\Dispatcher\MessageDispatcherInterface;
use IrishDan\NotificationBundle\Formatter\MessageFormatterInterface;
use IrishDan\NotificationBundle\Message\Message;
use IrishDan\NotificationBundle\Test\Entity\User;
use Symfony\Component\Yaml\Yaml;

class NotificationTestCase extends \PHPUnit_Framework_TestCase
{
    protected $testKernel;
    protected $parameters = [];

    protected function bootSymfony()
    {
        require_once __DIR__ . '/AppKernel.php';

        $this->testKernel = new \AppKernel('test', true);
        $this->testKernel->boot();
    }

    protected function getTestUser()
    {
        $user = new User();

        return $user;
    }

    protected function getTestNotification()
    {
        $notification = new TestNotification();

        return $notification;
    }

    protected function getNotificationWithUser()
    {
        $user = $this->getTestUser();
        $notification = $this->getTestNotification();

        $notification->setNotifiable($user);

        return $notification;
    }

    protected function getContainer()
    {
        if (empty($this->testKernel)) {
            $this->bootSymfony();
        }

        return $this->testKernel->getContainer();
    }

    protected function getMockFormatter($withMessage = false)
    {
        $formatter = $this->getMockBuilder(MessageFormatterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        if ($withMessage) {
            $formatter->expects($this->once())
                ->method('format')
                ->will($this->returnValue($this->getTestMessage()));
        }

        return $formatter;
    }

    protected function getMockDispatcher($withDispatch = false, $returnValue = true)
    {
        $dispatcher = $this->getMockBuilder(MessageDispatcherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        if ($withDispatch) {
            $dispatcher->expects($this->once())
                ->method('dispatch')
                ->will($this->returnValue($returnValue));
        }

        return $dispatcher;
    }

    protected function getTestMessage()
    {
        $message = new Message();
        $message->setDispatchData(['mail' => 'jim@jim.bob']);
        $message->setMessageData(
            [
                'title' => 'Hi!',
                'body' => 'Hi Jim, this is a notification',
            ]
        );

        return $message;
    }

    protected function getService($serviceName)
    {
        $container = $this->getContainer();

        return $container->get($serviceName);
    }

    protected function getParametersFromContainer($parameter)
    {
        $container = $this->getContainer();

        return $container->getParameter($parameter);
    }

    protected function getParameters($key = '')
    {
        if (empty($this->parameters)) {
            $path = __DIR__ . '/config_test.yml';
            $this->parameters = Yaml::parse(file_get_contents($path));
        }

        if (empty($key)) {
            return $this->parameters;
        }

        return empty($this->parameters[$key]) ? [] : $this->parameters[$key];
    }

    protected function getNotificationChannelConfiguration($key = '')
    {
        $config = $this->getParameters('notification');
        $config = $config['channels'];

        if (!empty($key)) {
            $config = empty($config[$key]) ? [] : $config[$key];
        }

        return $config;
    }

    protected function getToken()
    {
        return $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
    }
}