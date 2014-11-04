<?php

namespace LLS\Bundle\AWSBundle\Tests\Units\Factory;

use \mageekguy\atoum\test;

use LLS\Bundle\AWSBundle\Tests\Fixtures\AnnotatedModel;
use LLS\Bundle\AWSBundle\Factory;

use LLS\Bundle\AWSBundle\Interfaces\ClientFactoryInterface;
use LLS\Bundle\AWSBundle\Interfaces\IdentityInterface;

/**
 * Test class for Generic Identity Factory
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class GenericServiceFactory extends test
{
    protected $fqn;
    protected $instance;

    public function beforeTestMethod($method)
    {
        parent::beforeTestMethod($method);

        $this->fqn      = "LLS\\Bundle\\AWSBundle\\Tests\\Fixtures\\FakeService";
        $this->instance = new Factory\GenericServiceFactory($this->fqn);
    }

    public function testClass()
    {
        $this
            ->assert
                ->class("LLS\\Bundle\\AWSBundle\\Factory\\GenericServiceFactory")
                    ->hasInterface("LLS\\Bundle\\AWSBundle\\Interfaces\\ServiceFactoryInterface")
                    ->hasInterface("LLS\\Bundle\\AWSBundle\\Interfaces\\FactoryInterface")
        ;
    }

    public function testCreateService()
    {
        $data = array(
            new AnnotatedModel(),
            $this->getClientFactoryInterfaceMock()
        );

        $this
            ->assert
    
                ->object($service = $this->instance->create($data))
                    ->isInstanceOf($this->fqn)
                    ->isInstanceOf("LLS\\Bundle\\AWSBundle\\Interfaces\\ServiceInterface")
                    ->object($service->getIdentity())
                        ->isIdenticalTo($data[0])
                    ->object($service->getClientFactory())
                        ->isIdenticalTo($data[1])
        ;
    }
    
    public function testHandleService()
    {
        $identity      = $this->getIdentityInterfaceMock();
        $clientFactory = $this->getClientFactoryInterfaceMock();

        $this
            ->assert
    
                // Must not handle every services
    
                ->object($service = $this->getServiceInterfaceMock())
                    ->isInstanceOf('LLS\\Bundle\\AWSBundle\\Interfaces\\Serviceinterface')
                ->boolean($this->instance->handle($service))
                    ->isFalse()
    
                // Must handle the configured class
    
                ->object($service = new $this->fqn($identity, $clientFactory))
                    ->isInstanceOf('LLS\\Bundle\\AWSBundle\\Interfaces\\ServiceInterface')
                    ->isInstanceOf($this->fqn)
                ->boolean($this->instance->handle($service))
                    ->isTrue()
        ;
    }

    protected function getClientFactoryInterfaceMock()
    {
        return new \mock\LLS\Bundle\AWSBundle\Interfaces\ClientFactoryInterface();
    }

    protected function getIdentityInterfaceMock()
    {
        return new \mock\LLS\Bundle\AWSBundle\Interfaces\IdentityInterface();
    }

    protected function getServiceInterfaceMock()
    {
        $this->mockGenerator->orphanize('__construct');

        return new \mock\LLS\Bundle\AWSBundle\Interfaces\ServiceInterface();
    }
}