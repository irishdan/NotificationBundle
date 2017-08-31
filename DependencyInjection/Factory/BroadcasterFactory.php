<?php

namespace IrishDan\NotificationBundle\DependencyInjection\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class BroadcasterFactory
{
    public function create(ContainerBuilder $container, $name, array $config)
    {
        $type = array_keys($config)[0];
        $parameterName = 'notification.broadcast.config.' . $name;
        $serviceName = 'notification.broadcast.' . $name;
        $channelServiceName = 'notification.channel.' . $type;

        $container->setParameter($parameterName, $config[$type]);

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
    }
}
