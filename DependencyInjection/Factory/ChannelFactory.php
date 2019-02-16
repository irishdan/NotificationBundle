<?php

namespace IrishDan\NotificationBundle\DependencyInjection\Factory;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ChannelFactory
{
    protected $channelKey;
    protected $adapterName;
    protected $adapterConfiguration;
    protected $adapterConfigurationId;

    public function getChannelKeyAdapterMap()
    {
        return [
            'channel' => $this->channelKey,
            'adapter' => $this->adapterName,
            'config_id' => $this->adapterConfigurationId,
            'config' => $this->adapterConfiguration,
        ];
    }

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

        $adapterName = 'notification.adapter.' . $type;
        $adapter = new Reference($adapterName);
        $eventDispatcher = new Reference('event_dispatcher');

        $parameterName = 'notification.channel.' . $channel . '.configuration';


        if (!$container->hasParameter($parameterName)) {
            $container->setParameter($parameterName, $config);
        }

        $definition = new Definition();

        // Create a Channel or an EventChannel depending on the config.

        $definition->setClass('IrishDan\NotificationBundle\Channel\Channel');
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

        // @TODO We need to allow for both formatting and dispatching to be offloaded else where via events
        // @TODO The architecture used by event channel might be better suited to this. eg
        // every notification goes through the direct channel
        //

        // $definition->addMethodCall('setFormatAsEvent', [$config['channel_type'] === 'event']);
        $definition->addMethodCall('setDispatchAsEvent', [$config['channel_type'] === 'event']);

        $serviceName = 'notification.channel.' . $channel;
        $container->setDefinition($serviceName, $definition);

        $this->adapterConfigurationId = $parameterName;
        $this->adapterConfiguration = $config;
        $this->adapterName = $adapterName;
        $this->channelKey = $type;

        return $serviceName;
    }

    public function addConfiguration(ArrayNodeDefinition $node, $type)
    {
        switch ($type) {
            case 'mail':
                $node
                    ->children()
                    ->enumNode('channel_type')
                    ->values(['direct', 'event'])
                    ->defaultValue('direct')
                    ->end()
                    ->scalarNode('default_sender')->defaultValue('')->end()
                    ->arrayNode('cc')->end()
                    ->arrayNode('bcc')->end()
                    ->end();
                break;

            case 'database':
                $node
                    ->children()
                    ->enumNode('channel_type')
                    ->values(['direct', 'event'])
                    ->end()
                    ->scalarNode('entity')->defaultValue('App:Notification')->end()
                    ->end();
                break;
            case 'pusher':
                $node
                    ->children()
                    ->enumNode('channel_type')
                    ->values(['direct', 'event'])
                    ->defaultValue('direct')
                    ->end()
                    ->scalarNode('auth_key')->defaultValue('')->end()
                    ->scalarNode('secret')->defaultValue('')->end()
                    ->scalarNode('app_id')->defaultValue('')->end()
                    ->scalarNode('cluster')->defaultValue('')->end()
                    ->booleanNode('encrypted')->defaultTrue()->end()
                    ->scalarNode('channel_name')->defaultValue('')->end()
                    ->scalarNode('event')->defaultValue('')->end()
                    ->end();
                break;
            case 'nexmo':
                $node
                    ->children()
                    ->enumNode('channel_type')
                    ->values(['direct', 'event'])
                    ->defaultValue('direct')
                    ->end()
                    ->scalarNode('api_key')->defaultValue('')->end()
                    ->scalarNode('api_secret')->defaultValue('')->end()
                    ->scalarNode('from')->defaultValue('')->end()
                    ->end();
                break;
            case 'slack':
                $node
                    ->children()
                    ->enumNode('channel_type')
                    ->values(['direct', 'event'])
                    ->defaultValue('direct')
                    ->end()
                    ->scalarNode('webhook')->defaultNull()->end()
                    ->end();
                break;
            case 'logger':
                $node
                    ->children()
                    ->enumNode('channel_type')
                    ->values(['direct', 'event'])
                    ->defaultValue('direct')
                    ->end()
                    ->scalarNode('severity')->defaultValue('info')->end()
                    ->end();
                break;
            default:
                // Should allow any config
                // @TODO: The type key to define the allowed configuration
                break;
        }
    }
}
