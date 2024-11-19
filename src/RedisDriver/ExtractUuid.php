<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

use function count;
use function explode;

readonly class ExtractUuid
{
    public function fromKey(string $key): UuidInterface
    {
        $parts = explode('_', $key);

        return Uuid::fromString($parts[count($parts) - 1]);
    }
}
