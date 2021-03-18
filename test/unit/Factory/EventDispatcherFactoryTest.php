<?php

declare(strict_types=1);

namespace ArpTest\LaminasEvent\Factory;

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
     * Assert the factory is callable
     *
     * @throws Exception
     * @throws ExpectationFailedException
     */
    public function testIsCallable(): void
    {
        $factory = new EventDispatcherFactory();

        $this->assertIsCallable($factory);
    }
}
