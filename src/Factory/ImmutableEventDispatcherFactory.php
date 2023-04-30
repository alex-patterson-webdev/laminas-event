<?php

declare(strict_types=1);

namespace Arp\LaminasEvent\Factory;

use Arp\EventDispatcher\ImmutableEventDispatcher;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

final class ImmutableEventDispatcherFactory extends AbstractEventDispatcherFactory
{
    /**
     * @param ContainerInterface&ServiceLocatorInterface $container
     *
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        array $options = null
    ): ImmutableEventDispatcher {
        $options = $options ?? $this->getServiceOptions($container, $requestedName, 'event_dispatchers');

        $listenerProvider = $this->getListenerProvider(
            $container,
            $options['listener_provider'] ?? ListenerProviderInterface::class,
            $requestedName
        );

        return new ImmutableEventDispatcher($listenerProvider);
    }
}
