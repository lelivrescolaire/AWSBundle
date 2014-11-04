<?php
namespace LLS\Bundle\AWSBundle\Manager;

use LLS\Bundle\AWSBundle\Interfaces\ServiceInterface;
use LLS\Bundle\AWSBundle\Interfaces\ServiceFactoryInterface;
use LLS\Bundle\AWSBundle\Interfaces\ServiceManagerInterface;

/**
 * Perform operations on services
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class ServiceManager implements ServiceManagerInterface
{
    /**
     * @var array<ServiceFactoryInterface>
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
    public function getTypeName(ServiceInterface $service)
    {
        foreach ($this->types as $type => $factory) {
            if ($factory->handle($service)) {
                return $type;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function addType ($type, ServiceFactoryInterface $serviceFactory)
    {
        $this->validateTypeName($type, false);

        $this->types[$type] = $serviceFactory;

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
            throw new \Exception("Service type must be a valid string.");
        }

        if (($exists === true) && !isset($this->types[$type])) {
            throw new \Exception(sprintf("Undefined Service type '%s'.", $type));
        }

        if (($exists === false) && !empty($this->types[$type])) {
            throw new \Exception(sprintf("Service type '%s' already exists.", $type));
        }

        return true;
    }
}