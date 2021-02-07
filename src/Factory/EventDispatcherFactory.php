<?php

declare(strict_types=1);

namespace Arp\LaminasEvent\Factory;

use Arp\EventDispatcher\Factory\EventDispatcherFactory as ArpEventDispatcherFactory;
use Arp\Factory\Exception\FactoryException;
use Arp\LaminasFactory\AbstractFactory;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasEvent\Factory
 */
final class EventDispatcherFactory extends AbstractFactory
{
    /**
     * @noinspection PhpMissingParamTypeInspection
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EventDispatcherInterface
     *
     * @throws FactoryException
     *
     * @throws ServiceNotCreatedException
     * @throws ServiceNotFoundException
     */
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ): EventDispatcherInterface {
        $options = $options ?? $this->getServiceOptions($container, $requestedName, 'event_dispatchers');

        if (isset($options['listener_provider']) && is_string($options['listener_provider'])) {
            $options['listener_provider'] = $this->getService(
                $container,
                $options['listener_provider'],
                $requestedName
            );
        }

        return (new ArpEventDispatcherFactory())->create($options);
    }
}
