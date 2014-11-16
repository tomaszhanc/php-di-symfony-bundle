<?php

namespace DI\Bundle\Symfony\DependencyInjection\Loader;

class YamlFileLoader extends FileLoader
{
    protected function getDefinitionSourceClass()
    {
        return 'DI\Bundle\Symfony\Definition\Source\YamlFileDefinitionSource';
    }

} 