<?php

namespace IrishDan\NotificationBundle\DependencyInjection\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class BroadcasterFactory
{
    public function create(ContainerBuilder $container, $name, array $config)
    {
        $type = array_keys($config)[0];
        $parameterName = 'notification.broadcast.config.' . $name;
        $serviceName = 'notification.broadcast.' . $name;
        $channelServiceName = 'notification.channel.' . $type;

        // Create the configuration as a parameter.
        $container->setParameter($parameterName, $config[$type]);

        // Createe the broadcast service
        $definition = new Definition();
        $definition->setClass('IrishDan\NotificationBundle\Broadcast\Broadcaster');
        $definition->setArguments(
            [
                '@notification.broadcast.notifiable',
                '@' . $channelServiceName,
                '%' . $parameterName . '%',
            ]
        );

        $container->setDefinition($serviceName, $definition);

        // Add the broadcast to the notification manager.
        $notificationManager = $container->getDefinition('notification.manager');
        $notificationManager->addMethodCall('setBroadcaster', [$name, new Reference($serviceName)]);
    }
}
