<?php

declare(strict_types=1);

namespace Arp\LaminasEvent;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasEvent
 */
final class Module
{
    /**
     * @return mixed[]
     */
    public function getConfig(): array
    {
        return require __DIR__ . '/../config/module.config.php';
    }
}
