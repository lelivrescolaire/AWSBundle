<?php

namespace LLS\Bundle\AWSBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('llsaws');
        $rootNode
            ->children()
                ->booleanNode('config_auto_discovery')
                    ->defaultFalse()
                ->end()
            ->end()
            ->fixXmlConfig('identity')
            ->append($this->getIdentitiesNode())
            ->fixXmlConfig('service')
            ->append($this->getServicesNode());

        return $treeBuilder;
    }

    protected function getIdentitiesNode()
    {
        $treeBuilder = new TreeBuilder();

        $node = $treeBuilder->root('identities');
        $node
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('type')
                        ->defaultValue('user')
                    ->end()
                ->end()
                ->fixXmlConfig('field')
                ->children()
                    ->arrayNode('fields')
                        ->useAttributeAsKey('name')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end();

        return $node;
    }

    protected function getServicesNode()
    {
        $treeBuilder = new TreeBuilder();

        $node = $treeBuilder->root('services');
        $node
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('type')
                        ->isRequired()
                    ->end()
                    ->scalarNode('identity')
                        ->isRequired()
                    ->end()
                ->end()
            ->end();

        return $node;
    }
}
