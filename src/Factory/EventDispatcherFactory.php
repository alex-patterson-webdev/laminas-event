<?php

declare(strict_types=1);

namespace Arp\LaminasEvent\Factory;

use Arp\EventDispatcher\EventDispatcher;
use Arp\EventDispatcher\Listener\AddableListenerProviderInterface;
use Arp\LaminasEvent\Factory\Listener\ListenerRegistrationTrait;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class EventDispatcherFactory extends AbstractEventDispatcherFactory
{
    use ListenerRegistrationTrait;

    /**
     * @param ContainerInterface&ServiceLocatorInterface $container
     *
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        array $options = null
    ): EventDispatcherInterface {
        $options = $options ?? $this->getServiceOptions($container, $requestedName, 'event_dispatchers');

        $listenerProvider = $this->getListenerProvider(
            $container,
            $options['listener_provider'] ?? AddableListenerProviderInterface::class,
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

        if (!empty($options['listeners']) && is_array($options['listeners'])) {
            $this->registerEventListeners($container, $listenerProvider, $options['listeners'], $requestedName);
        }

        return new EventDispatcher($listenerProvider);
    }
}
