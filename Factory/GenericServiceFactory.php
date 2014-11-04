<?php
namespace LLS\Bundle\AWSBundle\Factory;

use LLS\Bundle\AWSBundle\Interfaces\ClientFactoryInterface;
use LLS\Bundle\AWSBundle\Interfaces\IdentityInterface;
use LLS\Bundle\AWSBundle\Interfaces\ModelInterface;
use LLS\Bundle\AWSBundle\Interfaces\ServiceInterface;
use LLS\Bundle\AWSBundle\Interfaces\ServiceFactoryInterface;

/**
 * Create Services
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class GenericServiceFactory implements ServiceFactoryInterface
{
    /**
     * @var string
     */
    protected $classFQN;

    /**
     * @var array
     */
    protected $arguments;

    /**
     * Constructor
     *
     * @param string $classFQN Identity class FQN
     */
    public function __construct($classFQN)
    {
        $this->classFQN  = $classFQN;
        $this->arguments = array_slice(func_get_args(), 1);
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $definition = null)
    {
        return $this->doCreate($definition[0], $definition[1]);
    }

    protected function doCreate(IdentityInterface $identity, ClientFactoryInterface $clientFactory)
    {
        $reflection = new \ReflectionClass($this->classFQN);

        return $reflection->newInstanceArgs(array($identity, $clientFactory));
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ModelInterface $service)
    {
        return ($service instanceof $this->classFQN);
    }
}