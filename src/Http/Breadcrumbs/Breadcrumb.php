<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Breadcrumbs;

readonly class Breadcrumb
{
    public function __construct(
        public string $content,
        public string|null $href = null,
    ) {
    }
}
