<?php

namespace LLS\Bundle\AWSBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use LLS\Bundle\AWSBundle\DependencyInjection\Compiler\IdentityCompilerPass;
use LLS\Bundle\AWSBundle\DependencyInjection\Compiler\ServiceCompilerPass;

/**
 * AWS API Base Bundle
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class LLSAWSBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new IdentityCompilerPass());
        $container->addCompilerPass(new ServiceCompilerPass());
    }
}
