<?php

declare(strict_types=1);

namespace ArpTest\LaminasEvent\Factory;

use Arp\EventDispatcher\EventDispatcher;
use Arp\EventDispatcher\Listener\AddableListenerProviderInterface;
use Arp\EventDispatcher\Listener\ListenerProvider;
use Arp\LaminasEvent\Factory\EventDispatcherFactory;
use Arp\LaminasFactory\FactoryInterface;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @covers \Arp\LaminasEvent\Factory\EventDispatcherFactory
 * @covers \Arp\LaminasEvent\Factory\AbstractEventDispatcherFactory
 */
final class EventDispatcherFactoryTest extends TestCase
{
    private ServiceLocatorInterface&MockObject $container;

    public function setUp(): void
    {
        $this->container = $this->createMock(ServiceLocatorInterface::class);
    }

    public function testIsCallable(): void
    {
        $factory = new EventDispatcherFactory();
        $this->assertIsCallable($factory);
    }

    public function testImplementsFactoryInterface(): void
    {
        $factory = new EventDispatcherFactory();
        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }

    /**
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testInvalidListenerProviderWillThrowServiceNotCreatedException(): void
    {
        $factory = new EventDispatcherFactory();

        $serviceName = EventDispatcherInterface::class;
        $options = [
            'listener_provider' => false,
        ];

        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage(
            sprintf(
                'The listener provider must be an object of type \'%s\'; \'%s\' provided for service \'%s\'',
                ListenerProviderInterface::class,
                'boolean',
                $serviceName
            )
        );

        $factory($this->container, $serviceName, $options);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ServiceNotCreatedException
     */
    public function testNonAddableListenerProviderWillThrowServiceNotCreatedException(): void
    {
        $factory = new EventDispatcherFactory();

        $serviceName = EventDispatcherInterface::class;
        $listenerProviderName = ListenerProviderInterface::class;
        $options = [
            'listener_provider' => $listenerProviderName,
        ];

        $this->container->expects($this->once())
            ->method('has')
            ->with($listenerProviderName)
            ->willReturn(true);

        /** @var ListenerProviderInterface&MockObject $listenerProvider */
        $listenerProvider = $this->createMock(ListenerProviderInterface::class);

        $this->container->expects($this->once())
            ->method('get')
            ->with($listenerProviderName)
            ->willReturn($listenerProvider);

        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage(
            sprintf(
                'The listener provider must be an object of type \'%s\'; \'%s\' provided for service \'%s\'',
                AddableListenerProviderInterface::class,
                get_class($listenerProvider),
                $serviceName
            )
        );

        $factory($this->container, $serviceName, $options);
    }

    /**
     * @dataProvider getEventDispatcherFactoryWillReturnConfiguredEventDispatcherData
     *
     * @param mixed $listenerProviderConfig
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ServiceNotCreatedException
     */
    public function testEventDispatcherFactoryWillReturnConfiguredEventDispatcher(mixed $listenerProviderConfig): void
    {
        $factory = new EventDispatcherFactory();

        $serviceName = EventDispatcherInterface::class;
        $options = [
            'listener_provider' => $listenerProviderConfig,
        ];

        /** @var AddableListenerProviderInterface&MockObject $listenerProvider */
        $listenerProvider = $this->createMock(AddableListenerProviderInterface::class);

        if (is_string($listenerProviderConfig)) {
            if (!class_exists($listenerProviderConfig)) {
                $this->container->expects($this->once())
                    ->method('has')
                    ->with($listenerProviderConfig)
                    ->willReturn(true);
            }
            $this->container->expects($this->once())
                ->method('get')
                ->with($listenerProviderConfig)
                ->willReturn($listenerProvider);
        } elseif (is_array($listenerProviderConfig)) {
            $this->container->expects($this->once())
                ->method('build')
                ->with(ListenerProviderInterface::class, $listenerProviderConfig)
                ->willReturn($listenerProvider);
        }

        $this->assertInstanceOf(EventDispatcher::class, $factory($this->container, $serviceName, $options));
    }

    /**
     * @return array<mixed>
     */
    public function getEventDispatcherFactoryWillReturnConfiguredEventDispatcherData(): array
    {
        return [
            [
                ListenerProvider::class,
            ],

            [
                [
                    'foo' => 'bar',
                    'test' => 'hello'
                ],
            ],

            [
                $this->createMock(AddableListenerProviderInterface::class),
            ]
        ];
    }
}
