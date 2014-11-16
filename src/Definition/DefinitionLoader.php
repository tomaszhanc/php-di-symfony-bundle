<?php

namespace DI\Bundle\Symfony\Definition;

use DI\ContainerBuilder;
use DI\Definition\Source\ChainableDefinitionSource;

class DefinitionLoader implements DefinitionLoaderInterface
{
    /** @var ChainableDefinitionSource[] */
    private $sources = [];

    public function addDefinitionSource(ChainableDefinitionSource $source)
    {
        $this->sources = $source;
    }

    public function load(ContainerBuilder $container)
    {
        if (empty($this->sources)) {
            return;
        }

        foreach ($this->sources as $source) {
            $container->addDefinitions($source);
        }
    }
} 