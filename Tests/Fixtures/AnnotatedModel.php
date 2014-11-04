<?php
namespace LLS\Bundle\AWSBundle\Tests\Fixtures;

use LLS\Bundle\AWSBundle\Annotation\Identity;
use LLS\Bundle\AWSBundle\Interfaces\IdentityInterface;

/**
 * Fixtured class for Annotation test purposes
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class AnnotatedModel implements IdentityInterface
{
    /**
     * Annotation with implicit name
     *
     * @Identity()
     */
    protected $implicitName = "implicitValue";

    /**
     * Annotation with explicit name
     *
     * @Identity(name="explicitName")
     */
    protected $randomName = "explicitValue";

    protected $foo = "virtualValue";

    public function getImplicitName()
    {
        return $this->implicitName;
    }

    public function setImplicitName($implicitValue)
    {
        $this->implicitName = $implicitValue;

        return $this;
    }

    public function getRandomName()
    {
        return $this->randomName;
    }

    public function setRandomName($randomName)
    {
        $this->randomName = $randomName;

        return $this;
    }

    /**
     * Virtual property getter
     *
     * @Identity(name="virtualName", type="getter")
     */
    public function fooGetter()
    {
        return $this->foo;
    }

    /**
     * Virtual property setter
     *
     * @Identity(name="virtualName", type="setter")
     */
    public function fooSetter($foo)
    {
        $this->foo = $foo;

        return $this;
    }
}