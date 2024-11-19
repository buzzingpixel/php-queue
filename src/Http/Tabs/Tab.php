<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Tabs;

readonly class Tab
{
    public function __construct(
        public string $name,
        public string $key,
        public bool $isActive,
    ) {
    }
}
