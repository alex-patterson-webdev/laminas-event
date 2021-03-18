<?php

declare(strict_types=1);

namespace Arp\LaminasEvent;

use Arp\EventDispatcher\EventDispatcher;
use Arp\EventDispatcher\Resolver\EventNameResolver;
use Arp\LaminasEvent\Factory\EventDispatcherFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Psr\EventDispatcher\EventDispatcherInterface;

return [
    'service_manager' => [
        'alias' => [
            EventDispatcher::class => EventDispatcherInterface::class,
        ],
        'factories' => [
            EventDispatcherInterface::class => EventDispatcherFactory::class,
            EventNameResolver::class => InvokableFactory::class,
        ],
        'shared' => [
            EventDispatcherInterface::class => false,
        ],
    ],
];
