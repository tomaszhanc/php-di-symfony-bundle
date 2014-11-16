<?php

namespace DI\Bundle\Symfony\DependencyInjection\Loader;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

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

    public function load($id, $file)
    {
        $class = $this->getDefinitionSourceClass();

        $definition = new Definition($class, [$this->locator->locate($file)]);
        $definition->addTag('di.definition_source');

        $this->container->setDefinition($id, $definition);
    }

    abstract protected function getDefinitionSourceClass();
} 