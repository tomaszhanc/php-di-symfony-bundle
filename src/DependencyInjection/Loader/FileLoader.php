<?php

namespace DI\Bundle\Symfony\DependencyInjection\Loader;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class FileLoader
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var FileLocatorInterface
     */
    private $locator;

    public function __construct(ContainerBuilder $container, FileLocatorInterface $locator)
    {
        $this->container = $container;
        $this->locator = $locator;
    }

    public function load($file)
    {
        $source = $this->createDefinitionSource($this->locator->locate($file));

        $loader = $this->container->getDefinition('di.definition_loader');
        $loader->addMethodCall('addDefinitionSource', $source);
    }

    abstract protected function createDefinitionSource($file);
} 