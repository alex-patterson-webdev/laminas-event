<?php

declare(strict_types=1);

namespace Arp\LaminasEvent;

final class Module
{
    /**
     * @return array<mixed>
     */
    public function getConfig(): array
    {
        return require __DIR__ . '/../config/module.config.php';
    }
}
