<?php
namespace Bean\Bundle\LocationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;

class BeanLocationBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
//        $container->addCompilerPass(new ValidationPass());
        $this->addRegisterMappingsPass($container);
    }
    /**
     * @param ContainerBuilder $container
     */
    private function addRegisterMappingsPass(ContainerBuilder $container)
    {
        $mappings = array(
            realpath(__DIR__ . '/Resources/config/doctrine-mapping') => 'Bean\Bundle\LocationBundle\Model',
        );
        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, array('bean_location.model_manager_name'), 'fos_user.backend_type_orm'));
        }
        if (class_exists('Doctrine\Bundle\MongoDBBundle\DependencyInjection\Compiler\DoctrineMongoDBMappingsPass')) {
            $container->addCompilerPass(DoctrineMongoDBMappingsPass::createXmlMappingDriver($mappings, array('bean_location.model_manager_name'), 'fos_user.backend_type_mongodb'));
        }
        if (class_exists('Doctrine\Bundle\CouchDBBundle\DependencyInjection\Compiler\DoctrineCouchDBMappingsPass')) {
            $container->addCompilerPass(DoctrineCouchDBMappingsPass::createXmlMappingDriver($mappings, array('bean_location.model_manager_name'), 'fos_user.backend_type_couchdb'));
        }
    }
}