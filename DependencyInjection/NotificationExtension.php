<?php

namespace IrishDan\NotificationBundle\DependencyInjection;

use IrishDan\NotificationBundle\DependencyInjection\Factory\Broadcaster\SlackBroadcasterFactory;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NotificationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }

        $enabledChannels = [];
        foreach ($config['channels'] as $channel => $channelConfig) {
            $enabledChannels[] = $channel;
            $container->setParameter('notification.channel.' . $channel . '.enabled', true);

            // Set a configuration parameter for each channel also.
            switch ($channel) {
                case 'mail':
                    $configuration = empty($channelConfig) ? [] : $channelConfig;
                    $container->setParameter('notification.channel.' . $channel . '.configuration', $configuration);

                    break;

                default:
                    $configuration = empty($channelConfig) ? [] : $channelConfig;
                    $container->setParameter('notification.channel.' . $channel . '.configuration', $configuration);
                    break;
            }

            // Create a service for this channel.
            $this->createChannelService($channel, $container);
        }


        // Create the channel service
        $this->createChannelManagerService($enabledChannels, $container);

        $container->setParameter('notification.available_channels', $enabledChannels);

        foreach ($config['broadcasters'] as $name => $config) {
            $adapters[$name] = $this->createBroadcaster($name, $config, $container);
        }
    }

    private function createChannelService($channel, ContainerBuilder $container)
    {
        $definition = new Definition();
        $definition->setClass('IrishDan\NotificationBundle\Channel\DefaultChannel');
        $definition->setArguments(
            [
                '%notification.channel.' . $channel . '.enabled%',
                '%notification.channel.' . $channel . '.configuration%',
            ]
        );
        $formatter  = new Reference('notification.' . $channel . '_data_formatter');
        $dispatcher = new Reference('notification.' . $channel . '_message_dispatcher');
        $definition->setMethodCalls(
            [
                ['setDataFormatter', [$formatter]],
                ['setDispatcher', [$dispatcher]],
            ]
        );
        $container->setDefinition('notification.channel.' . $channel, $definition);
    }

    private function createChannelManagerService(array $enabledChannels, ContainerBuilder $container)
    {
        $definition = new Definition();
        $definition->setClass('IrishDan\NotificationBundle\ChannelManager');
        $definition->setArguments(
            [
                new Reference('event_dispatcher'),
                $enabledChannels, // @TODO: can we reference a parameter
            ]
        );
        $container->setDefinition('notification.channel_manager', $definition);


        foreach ($enabledChannels as $channel) {
            // Add the channel to the channel manager service
            $channelManager = $container->getDefinition('notification.channel_manager');
            $channelId      = 'notification.channel.' . $channel;
            $channelManager->addMethodCall('setChannel', [$channel, new Reference($channelId)]);
        }
    }

    private function createBroadcaster($name, $broadcaster, $container)
    {
        //
    }

    private function mailChannelConfiguration($config)
    {
        // @TODO: If the email config is not set use the parameters.
        return $config;
    }
}