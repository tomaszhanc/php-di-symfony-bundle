<?php

namespace DI\Bundle\Symfony\DependencyInjection\Loader;

use DI\Definition\Source\PHPFileDefinitionSource;

class PhpFileLoader extends FileLoader
{
    protected function createDefinitionSource($file)
    {
        return new PHPFileDefinitionSource($file);
    }
} 