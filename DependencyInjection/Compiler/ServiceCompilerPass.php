<?php
namespace LLS\Bundle\AWSBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Push tagged identity factories into the identity manager
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class ServiceCompilerPass implements CompilerPassInterface
{
    /**
     * Process data
     *
     * @param ContainerBuilder $container SF2 Container Builder
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('llsaws.service.type');

        foreach ($taggedServices as $id => $tags) {
            $definition  = $container->getDefinition('llsaws.service.manager');
            $canonicalId = str_replace('.', '_', $id);

            foreach ($tags as $attributes) {
                $definition
                    ->addMethodCall(
                        'addType',
                        array(
                            !empty($attributes['alias']) ? $attributes['alias'] : $canonicalId,
                            new Reference($id)
                        )
                    );
            }
        }
    }
}