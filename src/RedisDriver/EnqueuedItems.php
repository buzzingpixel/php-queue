<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver;

use BuzzingPixel\Queue\QueueItemWithKey;
use BuzzingPixel\Queue\QueueItemWithKeyCollection;
use Redis;
use Symfony\Component\Cache\Adapter\RedisAdapter;

use function array_map;
use function sort;

readonly class EnqueuedItems
{
    public function __construct(
        private Redis $redis,
        private RedisAdapter $cachePool,
        private NamespaceFactory $namespaceFactory,
    ) {
    }

    public function inQueue(string $queueName): QueueItemWithKeyCollection
    {
        $enqueuedKeys = $this->redis->keys(
            $this->namespaceFactory->createKey(
                'queue',
                $queueName,
                '*',
            ),
        );

        sort($enqueuedKeys);

        $enqueuedKeysNoNamespace = $this->namespaceFactory->removeNamespaceFromKeys(
            $enqueuedKeys,
        );

        $queueItems = array_map(
            fn (string $key) => QueueItemWithKey::fromQueueItem(
                $queueName,
                $key,
                /** @phpstan-ignore-next-line */
                $this->cachePool->getItem($key)->get(),
            ),
            $enqueuedKeysNoNamespace,
        );

        return new QueueItemWithKeyCollection($queueItems);
    }
}
