<?php

declare(strict_types=1);

namespace Arp\LaminasEvent\Factory\Listener;

use Arp\EventDispatcher\Listener\AddListenerAwareInterface;
use Arp\EventDispatcher\Listener\AggregateListenerInterface;
use Arp\EventDispatcher\Listener\Exception\EventListenerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Psr\Container\ContainerInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasEvent\Factory\Listener
 */
trait ListenerRegistrationTrait
{
    /**
     * @param ContainerInterface        $container
     * @param AddListenerAwareInterface $collection
     * @param array<mixed>              $configs
     * @param string                    $serviceName
     *
     * @throws ServiceNotCreatedException
     */
    public function registerEventListeners(
        ContainerInterface $container,
        AddListenerAwareInterface $collection,
        array $configs,
        string $serviceName
    ): void {
        foreach ($configs as $eventName => $listeners) {
            if (is_string($listeners) && $container->has($listeners)) {
                $listeners = $container->get($listeners);
            }

            if ($listeners instanceof AggregateListenerInterface) {
                $listeners->addListeners($collection);
                continue;
            }

            if (!is_iterable($listeners)) {
                continue;
            }

            foreach ($listeners as $listener) {
                $priority = 1;

                if (is_array($listener)) {
                    $priority = isset($listener['priority']) ? (int)$listener['priority'] : $priority;
                    $listener = $listener['listener'] ?? null;
                }

                if (is_string($listener) && $container->has($listener)) {
                    $listener = $container->get($listener);
                }

                if (null === $listener || !is_callable($listener)) {
                    throw new ServiceNotCreatedException(
                        sprintf(
                            'Event listeners must be of type \'callable\'; '
                            . '\'%s\' provided when registering event \'%s::%s\'',
                            is_object($listener) ? get_class($listener) : gettype($listener),
                            $serviceName,
                            $eventName
                        )
                    );
                }

                try {
                    $collection->addListenerForEvent($eventName, $listener, $priority);
                } catch (EventListenerException $e) {
                    throw new ServiceNotCreatedException(
                        sprintf(
                            'Failed to add new event listener for event \'%s::%s\': %s',
                            $serviceName,
                            $eventName,
                            $e->getMessage()
                        ),
                        $e->getCode(),
                        $e
                    );
                }
            }
        }
    }
}
