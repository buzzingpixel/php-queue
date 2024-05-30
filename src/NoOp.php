<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

readonly class NoOp
{
    public function __invoke(): void
    {
    }
}
