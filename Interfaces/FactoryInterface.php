<?php
namespace LLS\Bundle\AWSBundle\Interfaces;

/**
 * Define Factories valid structure
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
interface FactoryInterface
{
    /**
     * Create a Model
     *
     * @param array|null $definition Model definition
     *
     * @return ModelInterface
     */
    public function create(array $definition = null);

    /**
     * Whether or not the factory can handle a model
     *
     * @param ModelInterface $model Model
     *
     * @return boolean
     */
    public function handle(ModelInterface $model);
}