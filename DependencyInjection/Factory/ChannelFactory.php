<?php

namespace IrishDan\NotificationBundle\DependencyInjection\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ChannelFactory
{
    public function create(ContainerBuilder $container, $channel, array $config, $type = '')
    {
        if (empty($type)) {
            $type = $channel;
        }

        // Merge config with type config
        if ($container->hasParameter('notification.channel.' . $type . '.configuration')) {
            $defaultConfig = $container->getParameter('notification.channel.' . $type . '.configuration');
            $config = array_merge($defaultConfig, $config);
        }

        $adapter = new Reference('notification.adapter.' . $type);
        $eventDispatcher = new Reference('event_dispatcher');

        $parameterName = 'notification.channel.' . $channel . '.configuration';
        if (!$container->hasParameter($parameterName)) {
            $container->setParameter($parameterName, $config);
        }

        $definition = new Definition();
        $definition->setClass('IrishDan\NotificationBundle\Channel\DirectChannel');
        $definition->setArguments(
            [
                '%' . $parameterName . '%',
                $channel,
                $adapter,
            ]
        );

        $definition->setMethodCalls(
            [
                ['setEventDispatcher', [$eventDispatcher]],
            ]
        );

        $definition->addMethodCall('setDispatchToEvent', [empty($config['direct_dispatch'])]);

        $serviceName = 'notification.channel.' . $channel;
        $container->setDefinition($serviceName, $definition);

        return $serviceName;
    }
}
