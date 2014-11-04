<?php
namespace LLS\Bundle\AWSBundle\Factory;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\PropertyAccess\PropertyAccess;

use LLS\Bundle\AWSBundle\Annotation\Identity;
use LLS\Bundle\AWSBundle\Interfaces\IdentityInterface;
use LLS\Bundle\AWSBundle\Interfaces\ModelInterface;
use LLS\Bundle\AWSBundle\Interfaces\IdentityFactoryInterface;

/**
 * Create Identities
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class GenericIdentityFactory implements IdentityFactoryInterface
{
    /**
     * @var PropertyAccess
     */
    protected $accessor;

    /**
     * @var string
     */
    protected $classFQN;

    /**
     * @var Reader
     */
    protected $reader;

    /**
     * Constructor
     *
     * @param string $classFQN Identity class FQN
     * @param Reader $reader   Doctrine annotation reader
     */
    public function __construct($classFQN, Reader $reader)
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->classFQN = $classFQN;
        $this->reader   = $reader;
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $definition = null)
    {
        $identity = new $this->classFQN();

        if (!empty($definition)) {
            $class      = $this->getIdentityReflection($identity);
            $methods    = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
            $properties = $class->getproperties();

            foreach ($methods as $method) {
                $annotations = $this->reader->getMethodAnnotations($method);

                foreach ($annotations as $annotation) {
                    switch (true)
                    {
                        case $annotation instanceof Identity:
                            if (!empty($annotation->name) && ($annotation->type == 'setter') && isset($definition[$annotation->name])) {
                                $method->invoke($identity, $definition[$annotation->name]);
                            }
                            break;
                    }
                }
            }

            foreach ($properties as $property) {
                $annotations = $this->reader->getPropertyAnnotations($property);

                foreach ($annotations as $annotation) {
                    switch (true)
                    {
                        case $annotation instanceof Identity:
                            if (empty($annotation->name)) {
                                $annotation->name = $property->getName();
                            }

                            if (isset($definition[$annotation->name])) {
                                $this->accessor->setValue($identity, $property->getName(), $definition[$annotation->name]);
                            }
                            break;
                    }
                }
            }
        }

        return $identity;
    }

    /**
     * {@inheritDoc}
     */
    public function export(IdentityInterface $identity)
    {
        if (!$this->handle($identity)) {
            throw new Error(sprintf(
                "Trying to handle identity of type '%s' with a factory designed for '%s'",
                get_class($identity),
                $this->classFQN
            ));
        }

        $identityArr = array();
        $class       = $this->getIdentityReflection($identity);
        $methods     = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
        $properties  = $class->getproperties();

        foreach ($methods as $method) {
            $annotations = $this->reader->getMethodAnnotations($method);

            foreach ($annotations as $annotation) {
                switch (true)
                {
                    case $annotation instanceof Identity:
                        if (!empty($annotation->name) && ($annotation->type == 'getter')) {
                            $identityArr[$annotation->name] = $method->invoke($identity);
                        }
                        break;
                }
            }
        }

        foreach ($properties as $property) {
            $annotations = $this->reader->getPropertyAnnotations($property);

            foreach ($annotations as $annotation) {
                switch (true)
                {
                    case $annotation instanceof Identity:
                        if (empty($annotation->name)) {
                            $annotation->name = $property->getName();
                        }

                        $identityArr[$annotation->name] = $this->accessor->getValue($identity, $property->getName());
                        break;
                }
            }
        }

        return $identityArr;
    }

    /**
     * Get Identity reflection class (ensure that $identity is an IdentityInterface)
     *
     * @param IdentityInterface $identity Identity model
     *
     * @return \ReflectionClass
     */
    protected function getIdentityReflection(IdentityInterface $identity)
    {
        return new \ReflectionClass($identity);
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ModelInterface $identity)
    {
        return ($identity instanceof $this->classFQN);
    }
}