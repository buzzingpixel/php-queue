<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http;

readonly class SidebarLink
{
    public function __construct(
        public string $content,
        public string $href,
        public bool $isActive = false,
    ) {
    }
}
