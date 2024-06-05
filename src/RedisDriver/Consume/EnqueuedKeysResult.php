<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver\Consume;

use function array_map;
use function array_values;
use function count;

readonly class EnqueuedKeysResult
{
    /** @var string[] */
    public array $enqueuedKeys;

    /** @param string[] $enqueuedKeys */
    public function __construct(array $enqueuedKeys)
    {
        $this->enqueuedKeys = array_values(array_map(
            static fn (string $k) => $k,
            $enqueuedKeys,
        ));
    }

    public function count(): int
    {
        return count($this->enqueuedKeys);
    }
}
