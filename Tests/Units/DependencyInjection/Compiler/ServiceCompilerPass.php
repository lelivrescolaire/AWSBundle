<?php

namespace LLS\Bundle\AWSBundle\Tests\Units\DependencyInjection\Compiler;

use LLS\Bundle\AWSBundle\DependencyInjection\Compiler\ServiceCompilerPass as CompilerPass;
use LLS\Bundle\AWSBundle\DependencyInjection\LLSAWSExtension as Extension;
use LLS\Bundle\AWSBundle\Tests\Utils\ContainerBuilderTest;

use Symfony\Component\DependencyInjection\Reference;

/**
 * Test class for ServiceCompilerPass
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class ServiceCompilerPass extends ContainerBuilderTest
{
    /**
     * @var Extension
     */
    protected $extension;

    /**
     * @var CompilerPass
     */
    protected $compilerPass;

    public function beforeTestMethod($method)
    {
        parent::beforeTestMethod($method);

        $this->compilerPass = new CompilerPass();
        $this->extension    = new Extension();
    }

    public function testLoadTaggedIdentityTypes()
    {
        $this->extension->load(array(), $this->container);

        $this->compilerPass->process($this->container);

        $this
            ->mock($this->container)
                 ->call('findTaggedServiceIds')
                    ->withArguments("llsaws.service.type")
                        ->once()
                ->call('getDefinition')
                    ->withArguments("llsaws.service.manager")
                        ->once();

        $this
            ->assert
                ->object($definition = $this->container->getDefinition("llsaws.service.manager"))
                    ->array($methods = $definition->getMethodCalls())
                        ->hasSize(1)
                        ->array($methods[0])
                            ->hasSize(2)
                            ->isEqualTo(array(
                                "addType",
                                array(
                                    "fake",
                                    new Reference("llsaws.service.type.fake")
                                )
                            ));
    }
}
