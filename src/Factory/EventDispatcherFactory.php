<?php

declare(strict_types=1);

namespace Arp\LaminasEvent\Factory;

use Arp\EventDispatcher\EventDispatcher;
use Arp\EventDispatcher\Listener\AddableListenerProviderInterface;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasEvent\Factory
 */
final class EventDispatcherFactory extends AbstractEventDispatcherFactory
{
    /**
     * @param ContainerInterface&ServiceLocatorInterface $container
     * @param string                                     $requestedName
     * @param array<mixed>|null                          $options
     *
     * @return EventDispatcherInterface
     *
     * @throws ServiceNotCreatedException
     * @throws ServiceNotFoundException
     */
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        array $options = null
    ): EventDispatcherInterface {
        $options = $options ?? $this->getServiceOptions($container, $requestedName, 'event_dispatchers');

        $listenerProvider = $this->getListenerProvider(
            $container,
            $options['listener_provider'] ?? ListenerProviderInterface::class,
            $requestedName
        );

        if (!$listenerProvider instanceof AddableListenerProviderInterface) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'The listener provider must be an object of type \'%s\'; \'%s\' provided for service \'%s\'',
                    AddableListenerProviderInterface::class,
                    get_class($listenerProvider),
                    $requestedName
                )
            );
        }

        return new EventDispatcher($listenerProvider);
    }
}
