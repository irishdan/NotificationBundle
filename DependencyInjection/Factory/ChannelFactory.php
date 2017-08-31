<?php

namespace IrishDan\NotificationBundle\DependencyInjection\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ChannelFactory
{
    public function create(ContainerBuilder $container, $channel)
    {
        $definition = new Definition();
        $definition->setClass('IrishDan\NotificationBundle\Channel\DirectChannel');
        $definition->setArguments(
            [
                '%notification.channel.' . $channel . '.enabled%',
                '%notification.channel.' . $channel . '.configuration%',
                $channel,
            ]
        );

        $adapter = new Reference('notification.adapter.' . $channel);
        $eventDispatcher = new Reference('event_dispatcher');

        $definition->setMethodCalls(
            [
                ['setAdapter', [$adapter]],
                ['setEventDispatcher', [$eventDispatcher]],
            ]
        );
        $container->setDefinition('notification.channel.' . $channel, $definition);
    }
}
