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
    protected $calls;

    /**
     * Constructor
     *
     * @param string $classFQN Identity class FQN
     */
    public function __construct($classFQN)
    {
        $this->classFQN  = $classFQN;
        $this->calls     = array_slice(func_get_args(), 1);
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

        $service = $reflection->newInstanceArgs(array($identity, $clientFactory));

        $this->execCalls($service);

        return $service;
    }

    protected function execCalls(ServiceInterface $service)
    {
        if ($this->calls) {
            foreach ($this->calls as $definition) {
                call_user_func_array(array($service, $definition[0]), array_slice($definition, 1));
            }
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ModelInterface $service)
    {
        return ($service instanceof $this->classFQN);
    }
}