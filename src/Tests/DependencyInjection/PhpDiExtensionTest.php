<?php

namespace DI\Bundle\Symfony\Tests\DependencyInjection;

use DI\Bundle\Symfony\DependencyInjection\PhpDiExtension;
use DI\Bundle\Symfony\Tests\TestCase;
use Doctrine\Common\Annotations\Reader;
use Mockery as M;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class PhpDiExtensionTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_load_services()
    {
        $container = new ContainerBuilder();
        $container->set('annotations.reader', M::mock(Reader::class));

        $extension = new PhpDiExtension();
        $extension->load([], $container);

        $container->get('di.controller_listener');
        $container->get('di.definition_loader');
    }
} 