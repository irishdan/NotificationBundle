<?php

namespace IrishDan\NotificationBundle\DependencyInjection\Factory\Broadcaster;

use IrishDan\NotificationBundle\DependencyInjection\Factory\BroadcasterFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class BroadcasterFactory implements BroadcasterFactoryInterface
{
    public function getKey()
    {
        return 'adapter';
    }

    public function create(ContainerBuilder $container, $id, array $config)
    {
        $container
            ->setDefinition($id, new DefinitionDecorator('oneup_flysystem.cache.adapter'))
            ->replaceArgument(0, new Reference(sprintf('oneup_flysystem.%s_adapter', $config['adapter'])))
            // ->replaceArgument(1, $config['key'])
            // ->replaceArgument(2, $config['expires'])
        ;
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('adapter')->isRequired()->end()
                ->scalarNode('key')->defaultValue('flysystem')->end()
                ->scalarNode('expires')->defaultNull()->end()
            ->end()
        ;
    }
}
