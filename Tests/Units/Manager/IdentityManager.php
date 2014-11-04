<?php

namespace LLS\Bundle\AWSBundle\Tests\Units\Manager;

use \mageekguy\atoum\test;

use Doctrine\Common\Annotations\AnnotationReader;

use LLS\Bundle\AWSBundle\Annotation\Identity;
use LLS\Bundle\AWSBundle\Manager;
use LLS\Bundle\AWSBundle\Tests\Fixtures\AnnotatedModel;

/**
 * Test class for Identity Manager
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class IdentityManager extends test
{
    protected $instance;

    public function beforeTestMethod($method)
    {
        parent::beforeTestMethod($method);

        new Identity(); // Fix Annotation not found error :(
        $this->instance = new Manager\IdentityManager();
    }

    public function testLinkFactories()
    {
        $factory = new \mock\LLS\Bundle\AWSBundle\Factory\GenericIdentityFactory(
            "LLS\\Bundle\\AWSBundle\\Tests\\Fixtures\\AnnotatedModel",
            new AnnotationReader()
        );

        $this
            ->assert

                // Add a Factory

                ->object($this->instance->addType('test', $factory))
                    ->isIdenticalTo($this->instance)

                // Get Identity type name

                ->string($this->instance->getTypeName(new AnnotatedModel()))
                    ->isEqualTo('test')
                ->variable($this->instance->getTypeName(new \mock\LLS\Bundle\AWSBundle\Interfaces\IdentityInterface()))
                    ->isNull()

                // Retrieve Factory

                ->object($this->instance->getFactory('test'))
                    ->isIdenticalTo($factory)
                ->variable($this->instance->getFactory('foo'))
                    ->isNull()
                

                // Create Identity

                ->object($this->instance->create('test', $definition = array("implicitName" => "foobar")))
                    ->mock($factory)
                        ->call("create")
                            ->withArguments($definition)
                                ->once
        ;
    }
}