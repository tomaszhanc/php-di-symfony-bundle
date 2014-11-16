<?php

namespace DI\Bundle\Symfony\Definition;

use DI\ContainerBuilder;
use DI\Definition\Source\ChainableDefinitionSource;

interface DefinitionLoaderInterface
{
    public function addDefinitionSource(ChainableDefinitionSource $source);

    public function load(ContainerBuilder $container);
} 