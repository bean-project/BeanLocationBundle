<?php

namespace Bean\Bundle\LocationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class BeanLocationExtension extends Extension
{
    /**
     * @var array
     */
    private static $doctrineDrivers = array(
        'orm' => array(
            'registry' => 'doctrine',
            'tag' => 'doctrine.event_subscriber',
        ),
        'mongodb' => array(
            'registry' => 'doctrine_mongodb',
            'tag' => 'doctrine_mongodb.odm.event_subscriber',
        ),
        'couchdb' => array(
            'registry' => 'doctrine_couchdb',
            'tag' => 'doctrine_couchdb.event_subscriber',
            'listener_class' => 'FOS\UserBundle\Doctrine\CouchDB\UserListener',
        ),
    );

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
		$loader->load('services.xml');

        if ('custom' !== $config['db_driver']) {
            if (isset(self::$doctrineDrivers[$config['db_driver']])) {
                $loader->load('doctrine.xml');
                $container->setAlias('bean_location.doctrine_registry', new Alias(self::$doctrineDrivers[$config['db_driver']]['registry'], false));
            } else {
                $loader->load(sprintf('%s.xml', $config['db_driver']));
            }
            $container->setParameter($this->getAlias() . '.backend_type_' . $config['db_driver'], true);
        }
        // Configure the factory for both Symfony 2.3 and 2.6+
        if (isset(self::$doctrineDrivers[$config['db_driver']])) {
            $definition = $container->getDefinition('bean_location.object_manager');
            if (method_exists($definition, 'setFactory')) {
                $definition->setFactory(array(new Reference('bean_location.doctrine_registry'), 'getManager'));
            } else {
                $definition->setFactoryService('bean_location.doctrine_registry');
                $definition->setFactoryMethod('getManager');
            }
        }

//        foreach (array('validator', 'security', 'util', 'mailer', 'listeners') as $basename) {
//            $loader->load(sprintf('%s.xml', $basename));
//        }

        ;

//        $container->setAlias('bean_location.user_manager', $config['service']['location_manager']);

//        if ($config['use_listener'] && isset(self::$doctrineDrivers[$config['db_driver']])) {
//            $listenerDefinition = $container->getDefinition('fos_user.user_listener');
//            $listenerDefinition->addTag(self::$doctrineDrivers[$config['db_driver']]['tag']);
//            if (isset(self::$doctrineDrivers[$config['db_driver']]['listener_class'])) {
//                $listenerDefinition->setClass(self::$doctrineDrivers[$config['db_driver']]['listener_class']);
//            }
//        }
        $this->remapParametersNamespaces($config, $container, array(
            ''          => array(
                'db_driver' => 'bean_location.storage',
                'model_manager_name' => 'bean_location.model_manager_name',
                'geolocation_class' => 'bean_location.model.geolocation.class',
            ),
        ));

    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param array            $map
     */
    protected function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
        foreach ($map as $name => $paramName) {
            if (array_key_exists($name, $config)) {
                $container->setParameter($paramName, $config[$name]);
            }
        }
    }
    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param array            $namespaces
     */
    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $ns => $map) {
            if ($ns) {
                if (!array_key_exists($ns, $config)) {
                    continue;
                }
                $namespaceConfig = $config[$ns];
            } else {
                $namespaceConfig = $config;
            }
            if (is_array($map)) {
                $this->remapParameters($namespaceConfig, $container, $map);
            } else {
                foreach ($namespaceConfig as $name => $value) {
                    $container->setParameter(sprintf($map, $name), $value);
                }
            }
        }
    }

}
