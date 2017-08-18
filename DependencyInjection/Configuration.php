<?php

namespace IrishDan\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package NotificationBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * The root configuration for responsive image bundle.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('notification');

        // Add basic configurations.
        $rootNode
            ->children()
                // Broadcasters
                ->arrayNode('broadcasters')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                    ->children()
                        ->arrayNode('slack')
                            ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                // Email channel settings.
                ->arrayNode('mail_channel')
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->scalarNode('default_sender')->defaultValue('')->end()
                    ->end()
                ->end()
                // Database channel.
                ->arrayNode('database_channel')
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->scalarNode('entity')
                            ->defaultValue('AppBundle:Notification')
                        ->end()
                    ->end()
                ->end()
                // Pusher notification channel
                ->arrayNode('pusher_channel')
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->scalarNode('auth_key')->defaultValue('')->end()
                        ->scalarNode('secret')->defaultValue('')->end()
                        ->scalarNode('app_id')->defaultValue('')->end()
                        ->scalarNode('cluster')->defaultValue('')->end()
                        ->booleanNode('encrypted')->defaultTrue()->end()
                        ->scalarNode('channel_name')->defaultValue('')->end()
                        ->scalarNode('event')->defaultValue('')->end()
                    ->end()
                ->end()
                // Nexmo sms channel
                ->arrayNode('nexmo_channel')
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->scalarNode('api_key')->defaultValue('')->end()
                        ->scalarNode('api_secret')->defaultValue('')->end()
                        ->scalarNode('from')->defaultValue('')->end()
                    ->end()
                ->end()
                // Database channel.
                ->arrayNode('slack_channel')
                    ->children()
                    ->booleanNode('enabled')->defaultFalse()->end()
                    ->end()
                    ->end()
                    ->end()
                ->end();

        return $treeBuilder;
    }
}