<?php

namespace IrishDan\NotificationBundle\DependencyInjection;

use IrishDan\NotificationBundle\DependencyInjection\Factory\BroadcasterFactory;
use IrishDan\NotificationBundle\DependencyInjection\Factory\ChannelFactory;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NotificationExtension extends Extension
{
    protected $channelKeyAdapterMappings = [];
    protected $defaultAdapters = [
        'mail',
        'logger',
        'database',
        'nexmo',
        'pusher',
        'slack',
    ];

    protected function setChannelAdapterMapping(array $maps)
    {
        $this->channelKeyAdapterMappings[$maps['channel']] = [
            'adapter' => $maps['adapter'],
            'config' => $maps['config'],
        ];
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        // Load our YAML resources
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        // @TODO: Broadcasters array should dynamic
        $configuration = new Configuration($this->defaultAdapters, ['slack', 'pusher']);
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }

        $enabledChannels = [];
        if (!empty($config['channels'])) {
            foreach ($config['channels'] as $channel => $channelConfig) {
                $enabledChannels[] = $channel;
                $container->setParameter('notification.channel.' . $channel . '.enabled', true);

                // Set a configuration parameter for each channel also.
                $parameters = empty($channelConfig) ? [] : $channelConfig;
                $parameterName = 'notification.channel.' . $channel . '.configuration';
                $container->setParameter($parameterName, $parameters);

                // Create a service for this channel.
                $this->createChannelService($channel, $container, $parameters);
            }
        }

        $container->setParameter('notification.available_channels', $enabledChannels);

        // Create the channel service
        $this->createChannelManagerService($enabledChannels, $container);

        // Create the notification manager service
        $this->createNotificationManagerService($container);

        // Create broadcasters and broadcast channels
        if (!empty($config['broadcasters'])) {
            foreach ($config['broadcasters'] as $name => $config) {
                $this->createBroadcaster($name, $config, $container);
            }
        }

        // Create the Event driven channel service
        $this->createEventDrivenChannel($container);

        // @TODO: Check that required parameters are set.
        foreach ($this->defaultAdapters as $type) {
            if (!$container->hasParameter('notification.channel.' . $type . '.configuration')) {
                $container->setParameter('notification.channel.' . $type . '.configuration', []);
                $container->setParameter('notification.channel.' . $type . '.enabled', false);
            }
        }
    }

    private function createEventDrivenChannel(ContainerBuilder $container)
    {
        $definition = new Definition();
        $definition->setClass('IrishDan\NotificationBundle\Channel\EventChannel');
        $definition->setArguments([new Reference('event_dispatcher')]);

        foreach ($this->channelKeyAdapterMappings as $key => $channel) {
            $definition->addMethodCall('setAdapters', [$key, new Reference($channel['adapter']), $container->getParameter($channel['config'])]);
        }

        $container->setDefinition('notification.channel.event_channel', $definition);
    }

    private function createChannelService($channel, ContainerBuilder $container, array $config)
    {
        $factory = new ChannelFactory();
        $factory->create($container, $channel, $config);
        $this->setChannelAdapterMapping($factory->getChannelKeyAdapterMap());
    }

    private function createBroadcaster($name, $config, ContainerBuilder $container)
    {
        $broadcastFactory = new BroadcasterFactory();
        $broadcastFactory->create($container, $name, $config);
    }

    private function createNotificationManagerService(ContainerBuilder $container)
    {
        $definition = new Definition();
        $definition->setClass('IrishDan\NotificationBundle\NotificationManager');
        $definition->setArguments(
            [
                new Reference('notification.channel_manager'),
                new Reference('notification.database_notification_manager'),
            ]
        );
        $container->setDefinition('notification.manager', $definition);
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