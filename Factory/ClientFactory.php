<?php
namespace LLS\Bundle\AWSBundle\Factory;

use LLS\Bundle\AWSBundle\Interfaces\ClientFactoryInterface;
use LLS\Bundle\AWSBundle\Interfaces\IdentityInterface;
use LLS\Bundle\AWSBundle\Interfaces\IdentityManagerInterface;

/**
 * Create AWS API clients
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class ClientFactory implements ClientFactoryInterface
{
    /**
     * @var IdentityManagerInterface
     */
    protected $identityManager;

    /**
     * Constructor
     *
     * @param IdentityManagerInterface $identityManager Identity manager, used to resolve authentication parameters
     */
    public function __construct(IdentityManagerInterface $identityManager)
    {
        $this->identityManager = $identityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function createClient($type, IdentityInterface $identity)
    {
        $class = $this->getClientClass($type);

        $identityType = $this->identityManager->getTypeName($identity);
        $factory      = $this->identityManager->getFactory($identityType);

        return $class::factory($factory->export($identity));
    }

    /**
     * {@inheritDoc}
     */
    public function getClientClass($type)
    {
        return sprintf('Aws\\%1$s\\%1$sClient', $type);
    }
}