<?php

declare(strict_types=1);

namespace ArpTest\LaminasEvent\Factory;

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
}
