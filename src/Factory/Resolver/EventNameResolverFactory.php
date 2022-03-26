<?php

declare(strict_types=1);

namespace Arp\LaminasEvent\Factory\Resolver;

use Arp\EventDispatcher\Resolver\EventNameResolver;
use Arp\LaminasFactory\AbstractFactory;
use Psr\Container\ContainerInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasEvent\Factory\Resolver
 */
class EventNameResolverFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array<mixed>|null  $options
     *
     * @return EventNameResolver
     */
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        array $options = null
    ): EventNameResolver {
        return new EventNameResolver();
    }
}
