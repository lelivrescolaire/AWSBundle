<?php
namespace LLS\Bundle\AWSBundle\Interfaces;

/**
 * Define Managers valid structure
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
interface ManagerInterface
{
    /**
     * Create a model
     *
     * @param string     $type       Factory type
     * @param array|null $definition Model definition
     *
     * @return ModelInterface
     */
    public function create($type, array $definition = null);

    /**
     * Get factory for a given type
     *
     * @param string $type Factory type name
     *
     * @return FactoryInterface
     */
    public function getFactory($type);

    /**
     * Get Identity type name
     *
     * @param ModelInterface $model Model
     *
     * @return string
     */
    public function getTypeName(ModelInterface $model);

    /**
     * Add an identity type
     *
     * @param string           $type    Type label
     * @param FactoryInterface $factory Factory
     *
     * @return {$this}
     */
    public function addType ($type, FactoryInterface $factory);
}