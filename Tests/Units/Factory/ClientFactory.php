<?php

namespace LLS\Bundle\AWSBundle\Tests\Units\Factory;

use \mageekguy\atoum\test;

use Doctrine\Common\Annotations\AnnotationReader;

use LLS\Bundle\AWSBundle\Annotation\Identity;
use LLS\Bundle\AWSBundle\Factory;

/**
 * Test class for Client Factory
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class ClientFactory extends test
{
    protected $fqn;
    protected $instance;

    public function beforeTestMethod($method)
    {
        parent::beforeTestMethod($method);

        $this->instance = new Factory\ClientFactory($this->getIdentityManagerInterfaceMock());
    }

    public function testInstanciate()
    {
        $this
            ->assert
                ->class("LLS\\Bundle\\AWSBundle\\Factory\\ClientFactory")
                    ->hasInterface("LLS\\Bundle\\AWSBundle\\Interfaces\\ClientFactoryInterface")
        ;
    }

    public function testGetServiceClassName()
    {
        $this
            ->assert

                ->string($this->instance->getClientClass('Sqs'))
                    ->isEqualTo('Aws\\Sqs\\SqsClient')
                ->string($this->instance->getClientClass('S3'))
                    ->isEqualTo('Aws\\S3\\S3Client')
        ;
    }

    protected function getIdentityManagerInterfaceMock()
    {
        $this->mockGenerator->orphanize('__construct');

        return new \mock\LLS\Bundle\AWSBundle\Interfaces\IdentityManagerInterface();
    }
}