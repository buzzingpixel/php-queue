<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver\Consume;

use BuzzingPixel\Queue\RedisDriver\NamespaceFactory;
use Redis;

use function sort;

readonly class EnqueuedKeys
{
    public function __construct(
        private Redis $redis,
        private NamespaceFactory $namespaceFactory,
    ) {
    }

    public function find(string $queueName): EnqueuedKeysResult
    {
        $enqueuedKeys = $this->redis->keys(
            $this->namespaceFactory->createKey(
                'queue',
                $queueName,
                '*',
            ),
        );

        sort($enqueuedKeys);

        return new EnqueuedKeysResult($enqueuedKeys);
    }
}
