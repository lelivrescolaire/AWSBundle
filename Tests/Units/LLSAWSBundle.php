<?php

namespace LLS\Bundle\AWSBundle\Tests\Units;

use LLS\Bundle\AWSBundle\DependencyInjection\Compiler\IdentityCompilerPass;
use LLS\Bundle\AWSBundle\DependencyInjection\Compiler\ServiceCompilerPass;
use LLS\Bundle\AWSBundle\LLSAWSBundle as Bundle;
use LLS\Bundle\AWSBundle\Tests\Utils\ContainerBuilderTest;

/**
 * Test class for LLSAWSBundle
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class LLSAWSBundle extends ContainerBuilderTest
{
    /**
     * @var Bundle
     */
    protected $bundle;

    public function beforeTestMethod($method)
    {
        parent::beforeTestMethod($method);

        $this->bundle = new Bundle();
    }

    public function testLoadCompilerPasses()
    {
        $this->bundle->build($this->container);

        $this
            ->mock($this->container)
                 ->call('addCompilerPass')
                    ->withArguments(
                        new IdentityCompilerPass()
                    )
                        ->once()
                    ->withArguments(
                        new ServiceCompilerPass()
                    )
                        ->once()
        ;
    }
}
