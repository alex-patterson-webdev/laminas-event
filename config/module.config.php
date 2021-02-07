<?php

declare(strict_types=1);

namespace Arp\LaminasEvent;

use Arp\EventDispatcher\EventDispatcher;
use Arp\EventDispatcher\Resolver\EventNameResolver;
use Arp\LaminasEvent\Factory\EventDispatcherFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'shared' => [
            EventDispatcher::class => false,
        ],
        'factories' => [
            EventNameResolver::class => InvokableFactory::class,
            EventDispatcher::class => EventDispatcherFactory::class,
        ],
    ],
];
