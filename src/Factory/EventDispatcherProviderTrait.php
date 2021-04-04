<?php

declare(strict_types=1);

namespace Arp\LaminasEvent\Factory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasEvent\Factory
 */
trait EventDispatcherProviderTrait
{
    /**
     * @param ServiceLocatorInterface                           $container
     * @param string|array<mixed>|EventDispatcherInterface|null $eventDispatcher
     * @param string                                            $serviceName
     *
     * @return EventDispatcherInterface
     *
     * @throws ServiceNotCreatedException
     * @throws ServiceNotFoundException
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
