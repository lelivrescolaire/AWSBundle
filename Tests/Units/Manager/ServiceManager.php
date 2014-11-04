<?php

namespace LLS\Bundle\AWSBundle\Tests\Units\Manager;

use \mageekguy\atoum\test;

use LLS\Bundle\AWSBundle\Manager;

/**
 * Test class for Identity Manager
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class ServiceManager extends test
{
    protected $instance;

    public function beforeTestMethod($method)
    {
        parent::beforeTestMethod($method);

        $this->instance = new Manager\ServiceManager();
    }

    public function testLinkFactories()
    {
        $factory = new \mock\LLS\Bundle\AWSBundle\Factory\GenericServiceFactory(
            "LLS\\Bundle\\AWSBundle\\Tests\\Fixtures\\FakeService"
        );

        $this
            ->assert

                // Add a Factory

                ->object($this->instance->addType('test', $factory))
                    ->isIdenticalTo($this->instance)

                // Get Identity type name

                ->string($this->instance->getTypeName($this->getFakeServiceMock()))
                    ->isEqualTo('test')
                ->variable($this->instance->getTypeName($this->getServiceInterfaceMock()))
                    ->isNull()

                // Retrieve Factory

                ->object($this->instance->getFactory('test'))
                    ->isIdenticalTo($factory)
                ->variable($this->instance->getFactory('foo'))
                    ->isNull()
                

                // Create Service

                ->object($this->instance->create(
                    'test',
                    $definition = array(
                        $this->getIdentityInterfaceMock(),
                        $this->getClientFactoryInterfaceMock()
                    )
                ))
                    ->mock($factory)
                        ->call("create")
                            ->withArguments($definition)
                                ->once
        ;
    }

    public function getFakeServiceMock()
    {
        $this->mockGenerator->orphanize('__construct');

        return new \mock\LLS\Bundle\AWSBundle\Tests\Fixtures\FakeService();
    }

    protected function getServiceInterfaceMock()
    {
        $this->mockGenerator->orphanize('__construct');

        return new \mock\LLS\Bundle\AWSBundle\Interfaces\ServiceInterface();
    }

    protected function getClientFactoryInterfaceMock()
    {
        return new \mock\LLS\Bundle\AWSBundle\Interfaces\ClientFactoryInterface();
    }

    protected function getIdentityInterfaceMock()
    {
        return new \mock\LLS\Bundle\AWSBundle\Interfaces\IdentityInterface();
    }
}