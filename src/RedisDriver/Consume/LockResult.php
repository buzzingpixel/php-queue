<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver\Consume;

readonly class LockResult
{
    public function __construct(
        public bool $hasLock = false,
        public string $lockKeyRaw = '',
        public string $consumeKeyRaw = '',
        public string $consumeKeySymfony = '',
    ) {
    }
}
