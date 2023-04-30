<?php

declare(strict_types=1);

namespace Arp\LaminasEvent\Factory\Resolver;

use Arp\EventDispatcher\Resolver\EventNameResolver;
use Arp\LaminasFactory\AbstractFactory;
use Psr\Container\ContainerInterface;

class EventNameResolverFactory extends AbstractFactory
{
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        array $options = null
    ): EventNameResolver {
        return new EventNameResolver();
    }
}
