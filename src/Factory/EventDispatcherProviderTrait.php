<?php

declare(strict_types=1);

namespace Arp\LaminasEvent\Factory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

trait EventDispatcherProviderTrait
{
    /**
     * @param string|array<mixed>|EventDispatcherInterface|null $eventDispatcher
     *
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getEventDispatcher(
        ServiceLocatorInterface $container,
        $eventDispatcher,
        string $serviceName
    ): EventDispatcherInterface {
        if (null === $eventDispatcher) {
            $eventDispatcher = [];
        }

        if (is_array($eventDispatcher)) {
            $eventDispatcher = $container->build(EventDispatcherInterface::class, $eventDispatcher);
        }

        if (is_string($eventDispatcher)) {
            $eventDispatcher = $container->get($eventDispatcher);
        }

        if (!$eventDispatcher instanceof EventDispatcherInterface) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'The event dispatcher must be an object of type \'%s\'; \'%s\' provided for service \'%s\'',
                    EventDispatcherInterface::class,
                    is_object($eventDispatcher) ? get_class($eventDispatcher) : gettype($eventDispatcher),
                    $serviceName
                )
            );
        }

        return $eventDispatcher;
    }
}
