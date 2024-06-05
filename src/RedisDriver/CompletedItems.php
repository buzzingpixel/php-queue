<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver;

use BuzzingPixel\Queue\QueueItemWithKeyCollection;
use Redis;
use Symfony\Component\Cache\Adapter\RedisAdapter;

use function array_map;
use function sort;

readonly class CompletedItems
{
    public function __construct(
        private Redis $redis,
        private RedisAdapter $cachePool,
        private NamespaceFactory $namespaceFactory,
    ) {
    }

    public function fromQueue(string $queueName): QueueItemWithKeyCollection
    {
        $completedKeys = $this->redis->keys(
            $this->namespaceFactory->createKey(
                'queue',
                'completed',
                $queueName,
                '*',
            ),
        );

        sort($completedKeys);

        $completedKeysNoNamespace = $this->namespaceFactory->removeNamespaceFromKeys(
            $completedKeys,
        );

        $queueItems = array_map(
            fn (string $key) => $this->cachePool->getItem($key)->get(),
            $completedKeysNoNamespace,
        );

        return new QueueItemWithKeyCollection($queueItems);
    }
}
