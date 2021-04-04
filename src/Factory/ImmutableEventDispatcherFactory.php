<?php

declare(strict_types=1);

namespace Arp\LaminasEvent\Factory;

use Arp\EventDispatcher\ImmutableEventDispatcher;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasEvent\Factory
 */
final class ImmutableEventDispatcherFactory extends AbstractEventDispatcherFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ImmutableEventDispatcher
     *
     * @throws ServiceNotCreatedException
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
