<?php
namespace LLS\Bundle\AWSBundle\Interfaces;

use LLS\Bundle\AWSBundle\Interfaces\IdentityInterface;

/**
 * Define ClientFactory's valid structure
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
interface ClientFactoryInterface
{
    /**
     * Create AWS client
     *
     * @param string            $type     Client type
     * @param IdentityInterface $identity Identity
     *
     * @return \Aws\Common\Client\AbstractClient
     */
    public function createClient($type, IdentityInterface $identity);

    /**
     * Get AWS Client class
     *
     * @param string $type Client type
     *
     * @return string
     */
    public function getClientClass($type);
}