<?php
namespace LLS\Bundle\AWSBundle\Interfaces;

/**
 * Define IdentityFactory's valid structure
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
interface IdentityFactoryInterface extends FactoryInterface
{
    /**
     * Convert identity object into an Array
     *
     * @param IdentityInterface $identity Identity model
     *
     * @return array
     */
    public function export(IdentityInterface $identity);
}