<?php

namespace NotificationBundle\DependencyInjection;

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
                // Push notification channel
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
            ->end();

        return $treeBuilder;
    }
}