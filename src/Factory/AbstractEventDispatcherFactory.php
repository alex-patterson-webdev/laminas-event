<?php

declare(strict_types=1);

namespace Arp\LaminasEvent\Factory;

use Arp\LaminasEvent\Factory\Listener\ListenerRegistrationTrait;
use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasEvent\Factory
 */
abstract class AbstractEventDispatcherFactory extends AbstractFactory
{
    use ListenerRegistrationTrait;

    /**
     * Attempt to resolve or build the AddableListenerProviderInterface
     *
     * @param ContainerInterface&ServiceLocatorInterface $container
     * @param ListenerProviderInterface|string|array     $listenerProviderConfig
     * @param string                                     $serviceName
     *
     * @return ListenerProviderInterface
     *
     * @throws ServiceNotCreatedException
     */
    protected function getListenerProvider(
        ContainerInterface $container,
        $listenerProviderConfig,
        string $serviceName
    ): ListenerProviderInterface {
        $listenerProvider = null;

        if (is_string($listenerProviderConfig)) {
            $listenerProvider = $this->getService($container, $listenerProviderConfig, $serviceName);
        }

        if (is_array($listenerProviderConfig)) {
            $listenerProvider = $this->buildService(
                $container,
                ListenerProviderInterface::class,
                $listenerProviderConfig,
                $serviceName
            );
        }

        if (!$listenerProvider instanceof ListenerProviderInterface) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'The listener provider must be an object of type \'%s\'; \'%s\' provided for service \'%s\'',
                    ListenerProviderInterface::class,
                    is_object($listenerProvider) ? get_class($listenerProvider) : gettype($listenerProvider),
                    $serviceName
                )
            );
        }

        return $listenerProvider;
    }
}
