<?php

namespace Bean\Bundle\LocationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bean_location');
        $supportedDrivers = array('orm', 'mongodb', 'couchdb', 'propel', 'custom');
        $rootNode
            ->children()
                ->scalarNode('db_driver')
                    ->validate()
                        ->ifNotInArray($supportedDrivers)
                        ->thenInvalid('The driver %s is not supported. Please choose one of ' . json_encode($supportedDrivers))
                    ->end()
                    ->cannotBeOverwritten()
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('geolocation_class')->defaultValue('Application\Bean\LocationBundle\Entity\Geolocation')->cannotBeEmpty()->end()
                ->scalarNode('model_manager_name')->defaultNull()->end()

        ;
        $this->addServiceSection($rootNode);
        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addServiceSection(ArrayNodeDefinition $node)
    {
        /**
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('service')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('location_manager')->defaultValue('bean_location.location_manager.default')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
         *
         */
    }

}
