<?php

namespace IrishDan\NotificationBundle\DependencyInjection\Factory;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ChannelFactory
{
    protected $channelKey;
    protected $adapterName;
    protected $adapterConfiguration;

    public function getChannelKeyAdapterMap()
    {
        return [
            'channel' => $this->channelKey,
            'adapter' => $this->adapterName,
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

        $this->adapterConfiguration = $parameterName;
        $this->adapterName = $adapterName;
        $this->channelKey = $type;

        return $serviceName;
    }

    public function addConfiguration(NodeDefinition $node, $type)
    {
        switch ($type) {
            case 'mail':
                $node
                    ->children()
                    ->scalarNode('default_sender')->defaultValue('')->end()
                    ->arrayNode('cc')->end()
                    ->arrayNode('bcc')->end()
                    ->end();
                break;

            case 'database':
                $node
                    ->children()
                    ->scalarNode('entity')->defaultValue('AppBundle:Notification')->end()
                    ->end();
                break;
            case 'pusher':
                $node
                    ->children()
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
                    ->scalarNode('api_key')->defaultValue('')->end()
                    ->scalarNode('api_secret')->defaultValue('')->end()
                    ->scalarNode('from')->defaultValue('')->end()
                    ->end();
                break;
            case 'slack':
                $node
                    ->children()
                    ->scalarNode('webhook')->defaultNull()->end()
                    ->end();
                break;
            case 'logger':
                $node
                    ->children()
                    ->scalarNode('type')->defaultValue('info')->end()
                    ->end();
                break;
            default:
                // Should allow any config
                // @TODO: The type key to define the allowed configuration
                break;
        }
    }
}
