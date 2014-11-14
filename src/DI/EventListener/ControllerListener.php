<?php

namespace DI\EventListener;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ControllerListener implements EventSubscriberInterface
{
	/** @var Reader */
	private $reader;

	public function __construct(Reader $reader)
	{
		$this->reader = $reader;
	}

	public function onKernelController(GetResponseEvent $event)
	{
		$request = $event->getRequest();

		if (!$request->attributes->has('_controller')) {
			// controller is not resolved
			return;
		}

		$controller = explode('::', $request->attributes->get('_controller'));

		if (count($controller) !== 2) {
			// works only for controller saved in format: 'Controller::Method'
			return;
		}

		$object = new \ReflectionClass($controller[0]);

		if (null !== $this->reader->getClassAnnotation($object, 'DI\Annotation\Injectable')) {
			// change controller format from class-method call to service-method call
			$request->attributes->set('_controller', implode(':', $controller));
		}
	}

	public static function getSubscribedEvents()
	{
		return array(
			KernelEvents::REQUEST => 'onKernelController'
		);
	}
}