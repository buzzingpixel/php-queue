<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver;

use Redis;

use function count;

readonly class TotalItems
{
    public function __construct(
        private Redis $redis,
        private NamespaceFactory $namespaceFactory,
    ) {
    }

    public function inAllQueues(): int
    {
        $enqueuedKeys = $this->redis->keys(
            $this->namespaceFactory->createKey('queue', '*'),
        );

        return count($enqueuedKeys);
    }

    public function inQueue(string $queueName): int
    {
        $enqueuedKeys = $this->redis->keys(
            $this->namespaceFactory->createKey(
                'queue',
                $queueName,
                '*',
            ),
        );

        return count($enqueuedKeys);
    }
}
