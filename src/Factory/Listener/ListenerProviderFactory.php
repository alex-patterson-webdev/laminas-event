<?php

declare(strict_types=1);

namespace Arp\LaminasEvent\Factory\Listener;

use Arp\EventDispatcher\Listener\AddableListenerProviderInterface;
use Arp\EventDispatcher\Listener\ListenerProvider;
use Arp\EventDispatcher\Resolver\EventNameResolverInterface;
use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasEvent\Factory\Listener
 */
class ListenerProviderFactory extends AbstractFactory
{
    use ListenerRegistrationTrait;

    /**
     * @var string
     */
    private string $defaultClassName = ListenerProvider::class;

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array<mixed>|null  $options
     *
     * @return ListenerProviderInterface
     *
     * @throws ServiceNotCreatedException
     */
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        array $options = null
    ): ListenerProviderInterface {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        $className = $options['class_name'] ?? $this->defaultClassName;

        $eventNameResolver = $this->getService(
            $container,
            $options['event_name_resolver'] ?? EventNameResolverInterface::class,
            $requestedName
        );

        /** @var ListenerProviderInterface $provider */
        $provider = new $className($eventNameResolver);

        if (!empty($options['listeners']) && $provider instanceof AddableListenerProviderInterface) {
            $this->registerEventListeners($container, $provider, $options['listeners'], $requestedName);
        }

        return $provider;
    }

    /**
     * @param string $defaultClassName
     */
    public function setDefaultClassName(string $defaultClassName): void
    {
        $this->defaultClassName = $defaultClassName;
    }
}
