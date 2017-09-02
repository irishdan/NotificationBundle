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

        $channelFactory = new ChannelFactory();
        $channelServiceName = $channelFactory->create($container, $name, $config[$type], $type);

        // Create the configuration as a parameter.
        $container->setParameter($parameterName, $config[$type]);

        // Create the broadcast service
        $definition = new Definition();
        $definition->setClass('IrishDan\NotificationBundle\Broadcast\Broadcaster');
        $definition->setArguments(
            [
                new Reference('notification.broadcast.notifiable'),
                new Reference($channelServiceName),
                '%' . $parameterName . '%',
            ]
        );

        $container->setDefinition($serviceName, $definition);

        // Add the broadcast to the notification manager.
        $container->findDefinition('notification.manager')->addMethodCall('setBroadcaster', [$name, new Reference($serviceName)]);
    }
}
