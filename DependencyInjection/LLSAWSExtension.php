<?php

namespace LLS\Bundle\AWSBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class LLSAWSExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if ($container->hasParameter('test') && $container->getParameter('test') === true) {
            $loader->load('services_test.yml');
        }

        if (isset($config['identities'])) {
            $this->loadIdentities($container, $config['identities']);
        }

        if (isset($config['services'])) {
            $this->loadServices($container, $config['services']);
        }
    }

    protected function loadIdentities(ContainerBuilder $container, array $config)
    {
        foreach ($config as $name => $infos) {
            $this->createIdentityService($container, $name, $infos['type'], $infos['fields']);
        }

        return $this;
    }

    protected function createIdentityService(ContainerBuilder $container, $name, $type, $definition)
    {
        $container
            ->setDefinition(
                self::getIdentityServiceKey($name),
                new Definition(
                    'LLS\\Bundle\\AWSBundle\\Interfaces\\IdentityInterface',
                    array(
                        $type,
                        $definition
                    )
                )
            )
            ->setFactoryService('llsaws.identity.manager')
            ->setFactoryMethod('create');

        return $this;
    }

    protected function loadServices(ContainerBuilder $container, array $config)
    {
        foreach ($config as $name => $infos) {
            $this->createServiceService($container, $name, $infos);
        }

        return $this;
    }

    protected function createServiceService(ContainerBuilder $container, $name, $attributes)
    {
        $container
            ->setDefinition(
                self::getServiceServiceKey($name),
                new Definition(
                    'LLS\\Bundle\\AWSBundle\\Interfaces\\ServiceInterface',
                    array(
                        $attributes['type'],
                        array(
                            new Reference(self::getIdentityServiceKey($attributes['identity'])),
                            new Reference('llsaws.client.factory')
                        )
                    )
                )
            )
            ->setFactoryService('llsaws.service.manager')
            ->setFactoryMethod('create');

        return $this;
    }

    /**
     * Get Identity Service key from it's name
     *
     * @param string $name Service name
     *
     * @return string
     */
    public static function getIdentityServiceKey($name)
    {
        return sprintf('llsaws.identities.%s', $name);
    }

    /**
     * Get Service Service key from it's name
     *
     * @param string $name Service name
     *
     * @return string
     */
    public static function getServiceServiceKey($name)
    {
        return sprintf('llsaws.services.%s', $name);
    }
}
