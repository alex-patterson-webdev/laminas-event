<?php

declare(strict_types=1);

namespace ArpTest\LaminasEvent\Factory;

use Arp\Factory\FactoryInterface;
use Arp\LaminasEvent\Factory\EventDispatcherFactory;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\LaminasEvent\Factory
 */
final class EventDispatcherFactoryTest extends TestCase
{
    /**
     * Assert the factory implements FactoryInterface
     *
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public function testImplementsFactoryInterface(): void
    {
        $factory = new EventDispatcherFactory();

        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }
}
