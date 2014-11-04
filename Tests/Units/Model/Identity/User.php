<?php

namespace LLS\Bundle\AWSBundle\Tests\Units\Model\Identity;

use \mageekguy\atoum\test;

use Doctrine\Common\Annotations\AnnotationReader;

use LLS\Bundle\AWSBundle\Annotation\Identity as IdentityAnnotation;
use LLS\Bundle\AWSBundle\Model\Identity;

/**
 * Test class for User Identity Model
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class User extends test
{
    protected $reader;

    public function beforeTestMethod($method)
    {
        parent::beforeTestMethod($method);

        new IdentityAnnotation(); // Fix Annotation not found error :(
        $this->reader   = new AnnotationReader();
    }

    public function testInstanciate()
    {
        $this
            ->assert
                ->object($user = new Identity\User())
                    ->isInstanceOf("LLS\\Bundle\\AWSBundle\\Interfaces\\IdentityInterface")
        ;
    }

    public function testGettersAndSetters()
    {
        $user = new Identity\User();

        $this
            ->assert

                // key

                ->object($user->setKey('myCustomKey'))
                    ->isIdenticalTo($user)
                ->string($user->getKey())
                    ->isIdenticalTo('myCustomKey')

                // secret

                ->object($user->setSecret('myPreciousSecret'))
                    ->isIdenticalTo($user)
                ->string($user->getSecret())
                    ->isIdenticalTo('myPreciousSecret')

                // region

                ->object($user->setRegion('myBeautifulRegion'))
                    ->isIdenticalTo($user)
                ->string($user->getRegion())
                    ->isIdenticalTo('myBeautifulRegion')
        ;
    }

    public function testHasGoodAnnotations()
    {
        $class = 'LLS\\Bundle\\AWSBundle\\Model\\Identity\\User';

        $this
            ->assert

                // Properties

                ->array($propAnnotations = $this->reader->getPropertyAnnotations(new \ReflectionProperty($class, 'key')))
                    ->hasSize(1)
                    ->object($keyAnnotation = $propAnnotations[0])
                        ->isInstanceOf('LLS\\Bundle\\AWSBundle\\Annotation\\Identity')
                        ->variable($keyAnnotation->name)
                            ->isNull()
                ->array($propAnnotations = $this->reader->getPropertyAnnotations(new \ReflectionProperty($class, 'secret')))
                    ->hasSize(1)
                    ->object($secretAnnotation = $propAnnotations[0])
                        ->isInstanceOf('LLS\\Bundle\\AWSBundle\\Annotation\\Identity')
                        ->variable($secretAnnotation->name)
                            ->isNull()

                // Methods

                ->array($propAnnotations = $this->reader->getMethodAnnotations(new \ReflectionMethod($class, 'getKey')))
                    ->hasSize(0)
                ->array($propAnnotations = $this->reader->getMethodAnnotations(new \ReflectionMethod($class, 'setKey')))
                    ->hasSize(0)
                ->array($propAnnotations = $this->reader->getMethodAnnotations(new \ReflectionMethod($class, 'getSecret')))
                    ->hasSize(0)
                ->array($propAnnotations = $this->reader->getMethodAnnotations(new \ReflectionMethod($class, 'setSecret')))
                    ->hasSize(0)
        ;
    }
}