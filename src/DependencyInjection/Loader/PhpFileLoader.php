<?php

namespace DI\Bundle\Symfony\DependencyInjection\Loader;

class PhpFileLoader extends FileLoader
{
    protected function getDefinitionSourceClass()
    {
        return 'DI\Definition\Source\PHPFileDefinitionSource';
    }
} 