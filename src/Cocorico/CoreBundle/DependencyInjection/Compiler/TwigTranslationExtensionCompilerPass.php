<?php

namespace Cocorico\CoreBundle\DependencyInjection\Compiler;

use Cocorico\CoreBundle\Twig\TranslationExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigTranslationExtensionCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        /**
         * Override Twig Translation extension
         */
        if ($container->hasDefinition('twig.extension.trans')) {
            $definition = $container->getDefinition('twig.extension.trans');
            $definition->setClass(TranslationExtension::class);
            $definition->addMethodCall(
                'setCheckTranslation',
                array($container->getParameter('cocorico.check_translation'))
            );
        }
    }
}
