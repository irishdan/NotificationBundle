<?php

namespace IrishDan\NotificationBundle\DependencyInjection;

use IrishDan\NotificationBundle\DependencyInjection\Factory\BroadcasterFactory;
use IrishDan\NotificationBundle\DependencyInjection\Factory\ChannelFactory;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NotificationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }

        $enabledChannels = [];
        foreach ($config['channels'] as $channel => $channelConfig) {
            $enabledChannels[] = $channel;
            $container->setParameter('notification.channel.' . $channel . '.enabled', true);

            // Set a configuration parameter for each channel also.
            $configuration = empty($channelConfig) ? [] : $channelConfig;
            $parameterName = 'notification.channel.' . $channel . '.configuration';
            $container->setParameter($parameterName, $configuration);

            // Create a service for this channel.
            $this->createChannelService($channel, $container);
        }

        $container->setParameter('notification.available_channels', $enabledChannels);

        // Create the channel service
        $this->createChannelManagerService($enabledChannels, $container);

        // Create services needd for broadcasting
        if (!empty($config['broadcasters'])) {
            foreach ($config['broadcasters'] as $name => $config) {
                $this->createBroadcaster($name, $config, $container);
            }
        }
    }

    private function createChannelService($channel, ContainerBuilder $container)
    {
        $factory = new ChannelFactory();
        $factory->create($container, $channel);
    }

    private function createBroadcaster($name, $config, ContainerBuilder $container)
    {
        $broadcastFactory = new BroadcasterFactory();
        $broadcastFactory->create($container, $name, $config);
    }

    private function createChannelManagerService(array $enabledChannels, ContainerBuilder $container)
    {
        $definition = new Definition();
        $definition->setClass('IrishDan\NotificationBundle\ChannelManager');
        $definition->setArguments(
            [
                new Reference('event_dispatcher'),
                $container->getParameter('notification.available_channels'),
            ]
        );
        $container->setDefinition('notification.channel_manager', $definition);


        foreach ($enabledChannels as $channel) {
            // Add the channel to the channel manager service
            $channelManager = $container->getDefinition('notification.channel_manager');
            $channelId = 'notification.channel.' . $channel;
            $channelManager->addMethodCall('setChannel', [$channel, new Reference($channelId)]);
        }
    }
}