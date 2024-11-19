<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver\Consume;

use BuzzingPixel\Queue\RedisDriver\NamespaceFactory;
use Redis;
use Symfony\Component\Cache\Adapter\RedisAdapter;

readonly class DeQueue
{
    public function __construct(
        private Redis $redis,
        private RedisAdapter $cachePool,
        private NamespaceFactory $namespaceFactory,
    ) {
    }

    public function one(string $key): bool
    {
        return $this->cachePool->deleteItem($key);
    }

    public function allItems(string $queueName = 'default'): bool
    {
        $enqueuedKeys = $this->redis->keys(
            $this->namespaceFactory->createKey(
                'queue',
                $queueName,
                '*',
            ),
        );

        return (bool) $this->redis->del($enqueuedKeys);
    }
}
