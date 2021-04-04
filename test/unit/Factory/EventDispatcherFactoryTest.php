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
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @covers  \Arp\LaminasEvent\Factory\EventDispatcherFactory
 * @covers  \Arp\LaminasEvent\Factory\AbstractEventDispatcherFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\LaminasEvent\Factory
 */
final class EventDispatcherFactoryTest extends TestCase
{
    /**
     * @var ServiceLocatorInterface&MockObject
     */
    private $container;

    /**
     * Prepare the test case dependencies
     */
    public function setUp(): void
    {
        $this->container = $this->createMock(ServiceLocatorInterface::class);
    }

    /**
     * Assert the factory is callable
     */
    public function testIsCallable(): void
    {
        $factory = new EventDispatcherFactory();

        $this->assertIsCallable($factory);
    }

    /**
     * Assert the class implement FactoryInterface
     */
    public function testImplementsFactoryInterface(): void
    {
        $factory = new EventDispatcherFactory();

        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }

    /**
     * @throws ServiceNotCreatedException
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
     * Assert that if provided with a ListenerProvider configuration that does not implement
     * AddableListenerProviderInterface a ServiceNotCreatedException will be thrown
     *
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
     * Assert that __invoke() will return a composed EventDispatcher instance
     *
     * @param mixed $listenerProviderConfig
     *
     * @dataProvider getEventDispatcherFactoryWillReturnConfiguredEventDispatcherData
     *
     * @throws ServiceNotCreatedException
     */
    public function testEventDispatcherFactoryWillReturnConfiguredEventDispatcher($listenerProviderConfig): void
    {
        $factory = new EventDispatcherFactory();

        $serviceName = EventDispatcherInterface::class;
        $options = [
            'listener_provider' => $listenerProviderConfig,
        ];

        /** @var AddableListenerProviderInterface&MockObject $listenerProvider */
        $listenerProvider = $this->createMock(AddableListenerProviderInterface::class);

        if (is_string($listenerProviderConfig)) {
            $this->container->expects($this->once())
                ->method('has')
                ->with($listenerProviderConfig)
                ->willReturn(true);

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
