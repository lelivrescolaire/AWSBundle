<?php

namespace LLS\Bundle\AWSBundle\Tests\Units\Factory;

use \mageekguy\atoum\test;

use Doctrine\Common\Annotations\AnnotationReader;

use LLS\Bundle\AWSBundle\Annotation\Identity;
use LLS\Bundle\AWSBundle\Factory;

/**
 * Test class for Generic Identity Factory
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class GenericIdentityFactory extends test
{
    protected $fqn;
    protected $instance;

    public function beforeTestMethod($method)
    {
        parent::beforeTestMethod($method);

        new Identity(); // Fix Annotation not found error :(
        $this->fqn      = "LLS\\Bundle\\AWSBundle\\Tests\\Fixtures\\AnnotatedModel";
        $this->instance = new Factory\GenericIdentityFactory($this->fqn, new AnnotationReader());
    }

    public function testInstanciate()
    {
        $this
            ->assert
                ->object($this->instance)
                    ->isInstanceOf("LLS\\Bundle\\AWSBundle\\Interfaces\\IdentityFactoryInterface")
                    ->isInstanceOf("LLS\\Bundle\\AWSBundle\\Interfaces\\FactoryInterface")
        ;
    }

    public function testCreateExportIdentity()
    {
        $data = array(
            "implicitName" => "myImplicitValue",
            "explicitName" => "myExplicitValue",
            "virtualName"  => "myVirtualValue",
        );

        $this
            ->assert

                // Create empty identity

                ->object($identity = $this->instance->create())
                    ->isInstanceOf($this->fqn)

                // Create filled identity

                ->object($identity = $this->instance->create($data))
                    ->isInstanceOf($this->fqn)
                    ->string($identity->getImplicitName())
                        ->isIdenticalTo($data['implicitName'])
                    ->string($identity->getRandomName())
                        ->isIdenticalTo($data['explicitName'])
                    ->string($identity->fooGetter())
                        ->isIdenticalTo($data['virtualName'])

                // Export identity informations

                ->array($informations = $this->instance->export($identity))
                    ->hasSize(3)
                    ->hasKeys(array_keys($data))
                    ->string($informations['implicitName'])
                        ->isIdenticalTo($identity->getImplicitName())
                    ->string($informations['explicitName'])
                        ->isIdenticalTo($identity->getRandomName())
                    ->string($data['virtualName'])
                        ->isIdenticalTo($identity->fooGetter())
        ;
    }

    public function testHandleIdentity()
    {
        $this
            ->assert

                // Must not handle every identities

                ->object($identity = new \mock\LLS\Bundle\AWSBundle\Interfaces\IdentityInterface())
                    ->isInstanceOf('LLS\\Bundle\\AWSBundle\\Interfaces\\IdentityInterface')
                ->boolean($this->instance->handle($identity))
                    ->isFalse()

                // Must handle the configured class

                ->object($identity = new $this->fqn())
                    ->isInstanceOf('LLS\\Bundle\\AWSBundle\\Interfaces\\IdentityInterface')
                    ->isInstanceOf($this->fqn)
                ->boolean($this->instance->handle($identity))
                    ->isTrue()
        ;
    }
}