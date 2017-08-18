<?php

namespace IrishDan\NotificationBundle\DependencyInjection;

use IrishDan\NotificationBundle\DependencyInjection\Factory\Broadcaster\SlackBroadcasterFactory;
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

        $channels        = ['mail_channel', 'database_channel', 'pusher_channel', 'nexmo_channel'];
        $enabledChannels = [];

        foreach ($channels as $channel) {
            // Set an enabled flag for each channel.
            if (!empty($config[$channel]['enabled'])) {
                $enabledChannels[] = $channel;
            }
            $container->setParameter('notification.' . $channel . '.enabled', !empty($config[$channel]['enabled']));

            // Set a configuration parameter for each channel also.
            switch ($channel) {
                case 'mail_channel':
                    if (!empty($config[$channel])) {
                        $configuration = $this->mailChannelConfiguration($config[$channel]);
                        $container->setParameter('notification.' . $channel . '.configuration', $configuration);
                    }
                    break;

                case 'database_channel':
                case 'pusher_channel':
                case 'nexmo_channel':
                    $configuration = empty($config[$channel]) ? [] : $config[$channel];
                    $container->setParameter('notification.' . $channel . '.configuration', $configuration);
                    break;
            }
        }

        $container->setParameter('notification.available_channels', $enabledChannels);

        // foreach ($config['channels'] as $name => $config) {
        //     $adapters[$name] = $this->createAdapter($name, $config, $container);
        // }

        foreach ($config['broadcasters'] as $name => $config) {
            $adapters[$name] = $this->createBroadcaster($name, $config, $container);
        }
    }

    private function createBroadcaster($name, $broadcaster, $container)
    {
        // var_dump($name);
        // var_dump($broadcaster);
//
        // $type = array_keys($broadcaster)[0];
        // var_dump($type);
        // // @TODO: Use to get the factory
        // $factory = new SlackBroadcasterFactory();

    }

    private function mailChannelConfiguration($config)
    {
        // @TODO: If the email config is not set use the parameters.

        return $config;
    }
}