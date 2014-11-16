<?php

namespace DI\Bundle\Symfony\Tests\EventListener;

use DI\Annotation\Injectable;
use DI\Bundle\Symfony\EventListener\ControllerListener;
use DI\Bundle\Symfony\Tests\TestCase;
use Doctrine\Common\Annotations\Reader;
use Mockery as M;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class ControllerListenerTest extends TestCase
{
    /** @var ControllerListener */
    private $listener;

    /** @var Reader | M\Mock */
    private $reader;

    /** @var Request */
    private $request;

    /** @var ParameterBag */
    private $attributes;

    protected function setUp()
    {
        $this->attributes = new ParameterBag();
        $this->request = new Request();
        $this->request->attributes = $this->attributes;

        $this->reader = M::mock(Reader::class);
        $this->listener = new ControllerListener($this->reader);
    }

    /**
     * @test
     */
    public function it_should_do_nothing_if_controller_attribute_does_not_exist()
    {
        $this->listener->onKernelController($this->getEvent());

        $this->assertFalse($this->attributes->has('_controller'));
    }

    /**
     * @test
     * @dataProvider getUnsupportedControllers
     *
     * @param string $controller  Unsupported value for _controller attribute.
     */
    public function it_should_do_nothing_if_controller_attribute_is_not_in_supported_format($controller)
    {
        $this->attributes->set('_controller', $controller);

        $this->listener->onKernelController($this->getEvent());

        $this->assertEquals($controller, $this->attributes->get('_controller'));
    }

    public function getUnsupportedControllers()
    {
        return [
            ['controller'],
            ['controller:method']
        ];
    }

    /**
     * @test
     */
    public function it_should_do_nothing_if_controller_is_not_annotated_by_injectable_annotation()
    {
        $this->attributes->set('_controller', 'stdClass::method');
        $this->given_controller_is_not_annotated_by_injectable_annotation();

        $this->listener->onKernelController($this->getEvent());

        $this->assertEquals('stdClass::method', $this->attributes->get('_controller'));
    }

    /**
     * @test
     */
    public function it_should_change_controller_attribute_to_use_service()
    {
        $this->attributes->set('_controller', 'stdClass::method');
        $this->given_controller_is_annotated_by_injectable_annotation();

        $this->listener->onKernelController($this->getEvent());

        $this->assertEquals('stdClass:method', $this->attributes->get('_controller'));
    }

    private function given_controller_is_not_annotated_by_injectable_annotation()
    {
        $this->reader
            ->shouldReceive('getClassAnnotation')
            ->andReturn(null)
            ->once();
    }

    private function given_controller_is_annotated_by_injectable_annotation()
    {
        $this->reader
            ->shouldReceive('getClassAnnotation')
            ->andReturn(new Injectable([]))
            ->once();
    }

    private function getEvent()
    {
        $kernel = M::mock(HttpKernelInterface::class);

        return new GetResponseEvent($kernel, $this->request, HttpKernelInterface::MASTER_REQUEST);
    }
} 