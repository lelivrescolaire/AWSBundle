<?php

namespace LLS\Bundle\AWSBundle\Tests\Units\DependencyInjection;

use \mageekguy\atoum\test;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

use LLS\Bundle\AWSBundle\DependencyInjection\LLSAWSExtension as Extension;
use LLS\Bundle\AWSBundle\Tests\Utils\ContainerBuilderTest;

/**
 * Test class for LLSAWSExtension
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class LLSAWSExtension extends ContainerBuilderTest
{
    /**
     * @var Extension
     */
    protected $extension;

    /**
     * Root name of the configuration
     *
     * @var string
     */
    protected $root;

    public function beforeTestMethod($method)
    {
        parent::beforeTestMethod($method);

        $this->extension = new Extension();
        $this->root      = "llsaws";
    }

    public function testGetConfigWithDefaultValues()
    {
        $this->extension->load(array(), $this->container);

        $this
            ->assert

                // Parameters

                ->string($this->container->getParameter($this->root.'.client.factory.class'))
                    ->isEqualTo('LLS\\Bundle\\AWSBundle\\Factory\\ClientFactory')

                ->string($this->container->getParameter($this->root.'.identity.manager.class'))
                    ->isEqualTo('LLS\\Bundle\\AWSBundle\\Manager\\IdentityManager')

                ->string($this->container->getParameter($this->root.'.identity.type.user.model.class'))
                    ->isEqualTo('LLS\\Bundle\\AWSBundle\\Model\\Identity\User')

                ->string($this->container->getParameter($this->root.'.identity.type.generic.factory.class'))
                    ->isEqualTo('LLS\\Bundle\\AWSBundle\\Factory\\GenericIdentityFactory')

                // Services

                ->boolean($this->container->hasDefinition($this->root.'.identity.manager'))
                    ->isTrue()
                    ->if($definition = $this->container->getDefinition($this->root.'.identity.manager'))
                    ->then
                        ->string($definition->getClass())
                            ->isEqualTo('%'.$this->root.'.identity.manager.class%')

                ->boolean($this->container->hasDefinition($this->root.'.client.factory'))
                    ->isTrue()
                    ->if($definition = $this->container->getDefinition($this->root.'.client.factory'))
                    ->then
                        ->string($definition->getClass())
                            ->isEqualTo('%'.$this->root.'.client.factory.class%')
                        ->if($arguments = $definition->getArguments())
                        ->then
                            ->array($arguments)
                                ->hasSize(1)
                            ->object($arguments[0])
                                ->isInstanceOf('Symfony\\Component\\DependencyInjection\\Reference')
                            ->string((string) $arguments[0])
                                ->isEqualTo($this->root.'.identity.manager')
                                

                ->boolean($this->container->hasDefinition($this->root.'.identity.type.user'))
                    ->isTrue()
                    ->if($definition = $this->container->getDefinition($this->root.'.identity.type.user'))
                    ->then
                        ->string($definition->getClass())
                            ->isEqualTo('%'.$this->root.'.identity.type.generic.factory.class%')

                        // Tags

                        ->array($tags = $definition->getTags())
                            ->hasSize(1)
                            ->hasKey($this->root.".identity.type")
                            ->array($tag = $tags[$this->root.".identity.type"])
                                ->hasSize(1)
                                    ->array($tag[0])
                                        ->hasSize(1)
                                        ->hasKey("alias")
                                        ->isIdenticalTo(array(
                                            "alias" => "user"
                                        ))

                        // Arguments

                        ->array($arguments = $definition->getArguments())
                            ->hasSize(2)
                        ->string($arguments[0])
                            ->isEqualTo('%'.$this->root.'.identity.type.user.model.class%')
                        ->object($arguments[1])
                            ->isInstanceOf('Symfony\\Component\\DependencyInjection\\Reference')
                        ->string((string) $arguments[1])
                            ->isEqualTo('annotation_reader')
                                

                ->boolean($this->container->hasDefinition($this->root.'.service.type.fake'))
                    ->isTrue()
                    ->if($definition = $this->container->getDefinition($this->root.'.service.type.fake'))
                    ->then
                        ->string($definition->getClass())
                            ->isEqualTo('%'.$this->root.'.service.type.generic.factory.class%')

                        // Tags

                        ->array($tags = $definition->getTags())
                            ->hasSize(1)
                            ->hasKey($this->root.".service.type")
                            ->array($tag = $tags[$this->root.".service.type"])
                                ->hasSize(1)
                                    ->array($tag[0])
                                        ->hasSize(1)
                                        ->hasKey("alias")
                                        ->isIdenticalTo(array(
                                            "alias" => "fake"
                                        ))

                        // Arguments

                        ->array($arguments = $definition->getArguments())
                            ->hasSize(1)
                        ->string($arguments[0])
                            ->isEqualTo('%'.$this->root.'.service.type.fake.model.class%');
    }

    public function testConfigCreateIdentities()
    {
        $configs = array(
            array(
                "identities" => array(
                    "sqs_jeremy" => array(
                        "fields" => array(
                            "key"    => "azerty",
                            "secret" => "qsddgfoxàc"
                        )
                    )
                )
            ),
            array(
                "identities" => array(
                    "sqs_jonathan" => array(
                        "type" => "myCustomType",
                        "fields" => array(
                            "key"    => "poiuiyt",
                            "secret" => "rtgiosdjgios"
                        )
                    )
                )
            ),
        );

        $this->extension->load($configs, $this->container);

        $this
            ->assert
                ->boolean($this->container->hasDefinition($this->root.'.identities.sqs_jeremy'))
                    ->isTrue()
                    ->if($definition = $this->container->getDefinition($this->root.'.identities.sqs_jeremy'))
                    ->then
                        ->string($definition->getClass())
                            ->isEqualTo('LLS\\Bundle\\AWSBundle\\Interfaces\\IdentityInterface')
                        ->string($definition->getFactoryService())
                            ->isEqualTo('llsaws.identity.manager')
                        ->string($definition->getFactoryMethod())
                            ->isEqualTo('create')
                        ->if($arguments = $definition->getArguments())
                        ->then
                            ->array($arguments)
                                ->hasSize(2)
                            ->string($arguments[0]) // Assert the default type uis "user"
                                ->isEqualTo('user')
                            ->array($arguments[1])
                                ->hasSize(2)
                                ->hasKeys(array("key", "secret"))
                                ->isIdenticalTo(array(
                                    "key"    => "azerty",
                                    "secret" => "qsddgfoxàc"
                                ))

                ->boolean($this->container->hasDefinition($this->root.'.identities.sqs_jonathan'))
                    ->isTrue()
                    ->if($definition = $this->container->getDefinition($this->root.'.identities.sqs_jonathan'))
                    ->then
                        ->string($definition->getClass())
                            ->isEqualTo('LLS\\Bundle\\AWSBundle\\Interfaces\\IdentityInterface')
                        ->string($definition->getFactoryService())
                            ->isEqualTo('llsaws.identity.manager')
                        ->string($definition->getFactoryMethod())
                            ->isEqualTo('create')
                        ->if($arguments = $definition->getArguments())
                        ->then
                            ->array($arguments)
                                ->hasSize(2)
                            ->string($arguments[0]) // Assert the default type uis "user"
                                ->isEqualTo('myCustomType')
                            ->array($arguments[1])
                                ->hasSize(2)
                                ->hasKeys(array("key", "secret"))
                                ->isIdenticalTo(array(
                                    "key"    => "poiuiyt",
                                    "secret" => "rtgiosdjgios"
                                ));
    }

    public function testConfigOverridesIdentities()
    {
        $configs = array(
            array(
                "identities" => array(
                    "sqs_jeremy" => array(
                        "fields" => array(
                            "key"    => "azerty",
                            "secret" => "qsddgfoxàc"
                        )
                    )
                )
            ),
            array(
                "identities" => array(
                    "sqs_jeremy" => array(
                        "type" => "myCustomType",
                        "fields" => array(
                            "secret" => "rtgiosdjgios"
                        )
                    )
                )
            ),
        );

        $this->extension->load($configs, $this->container);

        $this
            ->assert
                ->boolean($this->container->hasDefinition($this->root.'.identities.sqs_jeremy'))
                    ->isTrue()
                    ->if($definition = $this->container->getDefinition($this->root.'.identities.sqs_jeremy'))
                    ->then
                        ->string($definition->getClass())
                            ->isEqualTo('LLS\\Bundle\\AWSBundle\\Interfaces\\IdentityInterface')
                        ->string($definition->getFactoryService())
                            ->isEqualTo('llsaws.identity.manager')
                        ->string($definition->getFactoryMethod())
                            ->isEqualTo('create')
                        ->if($arguments = $definition->getArguments())
                        ->then
                            ->array($arguments)
                                ->hasSize(2)
                            ->string($arguments[0]) // Assert the default type uis "user"
                                ->isEqualTo('myCustomType')
                            ->array($arguments[1])
                                ->hasSize(2)
                                ->hasKeys(array("key", "secret"))
                                ->isIdenticalTo(array(
                                    "key"    => "azerty",
                                    "secret" => "rtgiosdjgios"
                                ));
    }

    public function testConfigCreateServices()
    {
        $configs = array(
            array(
                "services" => array(
                    "fake_service" => array(
                        "type"     => "fake",
                        "identity" => "jeremy"
                    )
                )
            ),
            array(
                "services" => array(
                    "other_service" => array(
                        "type"     => "other_type",
                        "identity" => "jonathan"
                    )
                )
            ),
        );

        $this->extension->load($configs, $this->container);

        $this
            ->assert
                ->boolean($this->container->hasDefinition($this->root.'.services.fake_service'))
                    ->isTrue()
                    ->if($definition = $this->container->getDefinition($this->root.'.services.fake_service'))
                    ->then
                        ->string($definition->getClass())
                            ->isEqualTo('LLS\\Bundle\\AWSBundle\\Interfaces\\ServiceInterface')
                        ->string($definition->getFactoryService())
                            ->isEqualTo('llsaws.service.manager')
                        ->string($definition->getFactoryMethod())
                            ->isEqualTo('create')
                        ->if($arguments = $definition->getArguments())
                        ->then
                            ->array($arguments)
                                ->hasSize(2)
                            ->string($arguments[0])
                                ->isEqualTo('fake')
                            ->array($arguments[1])
                                ->hasSize(2)
                                ->isEqualTo(array(
                                    new Reference('llsaws.identities.jeremy'),
                                    new Reference('llsaws.client.factory')
                                ))

                ->boolean($this->container->hasDefinition($this->root.'.services.other_service'))
                    ->isTrue()
                    ->if($definition = $this->container->getDefinition($this->root.'.services.other_service'))
                    ->then
                        ->string($definition->getClass())
                            ->isEqualTo('LLS\\Bundle\\AWSBundle\\Interfaces\\ServiceInterface')
                        ->string($definition->getFactoryService())
                            ->isEqualTo('llsaws.service.manager')
                        ->string($definition->getFactoryMethod())
                            ->isEqualTo('create')
                        ->if($arguments = $definition->getArguments())
                        ->then
                            ->array($arguments)
                                ->hasSize(2)
                            ->string($arguments[0])
                                ->isEqualTo('other_type')
                            ->array($arguments[1])
                                ->hasSize(2)
                                ->isEqualTo(array(
                                    new Reference('llsaws.identities.jonathan'),
                                   
                                    new Reference('llsaws.client.factory')
                                ));
    }

    public function testConfigOverridesServices()
    {
        $configs = array(
            array(
                "services" => array(
                    "fake_service" => array(
                        "type"     => "fake",
                        "identity" => "jeremy"
                    )
                )
            ),
            array(
                "services" => array(
                    "fake_service" => array(
                        "identity" => "jonathan"
                    )
                )
            )
        );
        
        $this->extension->load($configs, $this->container);
        
        $this
            ->assert
                ->boolean($this->container->hasDefinition($this->root.'.services.fake_service'))
                    ->isTrue()
                    ->if($definition = $this->container->getDefinition($this->root.'.services.fake_service'))
                    ->then
                        ->string($definition->getClass())
                            ->isEqualTo('LLS\\Bundle\\AWSBundle\\Interfaces\\ServiceInterface')
                        ->string($definition->getFactoryService())
                            ->isEqualTo('llsaws.service.manager')
                        ->string($definition->getFactoryMethod())
                            ->isEqualTo('create')
                        ->if($arguments = $definition->getArguments())
                        ->then
                            ->array($arguments)
                                ->hasSize(2)
                            ->string($arguments[0])
                                ->isEqualTo('fake')
                            ->array($arguments[1])
                                ->hasSize(2)
                                ->isEqualTo(array(
                                    new Reference("llsaws.identities.jonathan"),
                                    new Reference('llsaws.client.factory')
                                ));
    }
}
