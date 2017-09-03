<?php

namespace IrishDan\NotificationBundle\DependencyInjection;

use IrishDan\NotificationBundle\DependencyInjection\Factory\BroadcasterFactory;
use IrishDan\NotificationBundle\DependencyInjection\Factory\ChannelFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package NotificationBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    private $defaultAdapterTypes;
    private $broadcasters;

    public function __construct(array $defaultAdapterTypes = [], $broadcasters = [])
    {
        $this->defaultAdapterTypes = $defaultAdapterTypes;
        $this->broadcasters = $broadcasters;
    }

    private function addChannelsSection(ArrayNodeDefinition $node)
    {
        $channelNodeBuilder = $node
            ->children()
                ->arrayNode('channels')
                ->performNoDeepMerging()
                ->children();

        $factory = new ChannelFactory();
        foreach ($this->defaultAdapterTypes as $type) {
            $factoryNode = $channelNodeBuilder->arrayNode($type)->canBeUnset();
            $factory->addConfiguration($factoryNode, $type);
        }
    }

    private function addBroadcastChannelsSection(ArrayNodeDefinition $node)
    {
        $broadcastNodeBuilder = $node
            ->children()
                ->arrayNode('broadcasters')
                    // ->useAttributeAsKey('name')
                    ->prototype('array')
                    ->performNoDeepMerging()
                    ->children()
        ;

        foreach ($this->broadcasters as $name) {
            $broadcastNodeBuilder->arrayNode($name)
                ->prototype('scalar')->end()
                ->end();
        }
    }

    /**
     * The root configuration for responsive image bundle.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('notification');


        $this->addChannelsSection($rootNode);
        $this->addBroadcastChannelsSection($rootNode);

        $rootNode
            ->children()
            ->end();

        return $treeBuilder;
    }
}