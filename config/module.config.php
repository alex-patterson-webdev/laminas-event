<?php

declare(strict_types=1);

namespace Arp\LaminasEvent;

use Arp\EventDispatcher\EventDispatcher;
use Arp\EventDispatcher\ImmutableEventDispatcher;
use Arp\EventDispatcher\Listener\AddableListenerProviderInterface;
use Arp\EventDispatcher\Listener\ListenerProvider;
use Arp\EventDispatcher\Resolver\EventNameResolver;
use Arp\EventDispatcher\Resolver\EventNameResolverInterface;
use Arp\LaminasEvent\Factory\EventDispatcherFactory;
use Arp\LaminasEvent\Factory\ImmutableEventDispatcherFactory;
use Arp\LaminasEvent\Factory\Listener\ListenerProviderFactory;
use Arp\LaminasEvent\Factory\Resolver\EventNameResolverFactory;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

return [
    'service_manager' => [
        'shared' => [
            EventDispatcherInterface::class => false,
            ListenerProviderInterface::class => false,
            ImmutableEventDispatcher::class => false,
        ],
        'aliases' => [
            EventDispatcher::class => EventDispatcherInterface::class,
            ListenerProvider::class => ListenerProviderInterface::class,
            AddableListenerProviderInterface::class => ListenerProviderInterface::class,
            EventNameResolver::class => EventNameResolverInterface::class,
        ],
        'factories' => [
            EventDispatcherInterface::class => EventDispatcherFactory::class,
            ImmutableEventDispatcher::class => ImmutableEventDispatcherFactory::class,

            ListenerProviderInterface::class => ListenerProviderFactory::class,
            EventNameResolverInterface::class => EventNameResolverFactory::class,
        ],
    ],
];
