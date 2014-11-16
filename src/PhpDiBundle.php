<?php

namespace DI\Bundle\Symfony;

use DI\Bundle\Symfony\DependencyInjection\Compiler\DefinitionSourcePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PhpDiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DefinitionSourcePass());
    }

}