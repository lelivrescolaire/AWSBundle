<?php
namespace LLS\Bundle\AWSBundle\Interfaces;

use LLS\Bundle\AWSBundle\Interfaces\ClientFactoryInterface;
use LLS\Bundle\AWSBundle\Interfaces\IdentityInterface;

/**
 * Define AWS Bundle Services requirements
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
interface ServiceInterface extends ModelInterface
{
    /**
     * Constructor
     *
     * @param IdentityInterface      $identity      Service's identity
     * @param ClientFactoryInterface $clientFactory Client Factory
     */
    public function __construct(IdentityInterface $identity, ClientFactoryInterface $clientFactory);
}