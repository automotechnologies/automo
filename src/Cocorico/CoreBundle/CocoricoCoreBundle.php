<?php

namespace Cocorico\CoreBundle;

use Cocorico\CoreBundle\DependencyInjection\Compiler\TwigTranslationExtensionCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class CocoricoCoreBundle
 *
 */
class CocoricoCoreBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new TwigTranslationExtensionCompilerPass());
    }
}
