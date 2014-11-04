<?php
namespace LLS\Bundle\AWSBundle\Manager;

use LLS\Bundle\AWSBundle\Interfaces\FactoryInterface;
use LLS\Bundle\AWSBundle\Interfaces\IdentityInterface;
use LLS\Bundle\AWSBundle\Interfaces\IdentityFactoryInterface;
use LLS\Bundle\AWSBundle\Interfaces\IdentityManagerInterface;
use LLS\Bundle\AWSBundle\Interfaces\ModelInterface;

/**
 * Perform operations on identities
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class IdentityManager implements IdentityManagerInterface
{
    /**
     * @var array<IdentityFactoryInterface>
     */
    protected $types = array();

    /**
     * {@inheritDoc}
     */
    public function create($type, array $definition = null)
    {
        if ($factory = $this->getFactory($type)) {
            return $factory->create($definition);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getFactory($type)
    {
        $this->validateTypeName($type);

        return isset($this->types[$type]) ? $this->types[$type] : null;
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeName(ModelInterface $identity)
    {
        foreach ($this->types as $type => $factory) {
            if ($factory->handle($identity)) {
                return $type;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function addType ($type, FactoryInterface $identityFactory)
    {
        $this->validateTypeName($type, false);

        $this->types[$type] = $identityFactory;

        return $this;
    }

    /**
     * Ensure type name is valid
     *
     * @param string       $type   Type name
     * @param boolean|null $exists Whether or not the type exists
     *
     * @return boolean
     */
    protected function validateTypeName($type, $exists = null)
    {
        if (empty($type) && !is_string($type)) {
            throw new \Exception("Identity type must be a valid string.");
        }

        if (($exists === true) && !isset($this->types[$type])) {
            throw new \Exception(sprintf("Undefined Identity type '%s'.", $type));
        }

        if (($exists === false) && !empty($this->types[$type])) {
            throw new \Exception(sprintf("Identity type '%s' already exists.", $type));
        }

        return true;
    }
}