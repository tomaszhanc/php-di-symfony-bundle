<?php

namespace DI\Bundle\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DefinitionSourcePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('di.definition_loader')) {
            return;
        }

        $loader = $container->getDefinition('di.definition_loader');
        $definitions = $container->findTaggedServiceIds('di.definition_source');

        foreach ($definitions as $id => $service) {
            $loader->addMethodCall('addDefinitionSource', [new Reference($id)]);
        }
    }

} 