<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver;

use BuzzingPixel\Queue\QueueItemFailedCollection;
use Redis;
use Symfony\Component\Cache\Adapter\RedisAdapter;

use function array_map;
use function sort;

readonly class FailedItems
{
    public function __construct(
        private Redis $redis,
        private RedisAdapter $cachePool,
        private NamespaceFactory $namespaceFactory,
    ) {
    }

    public function fromQueue(string $queueName): QueueItemFailedCollection
    {
        $failedKeys = $this->redis->keys(
            $this->namespaceFactory->createKey(
                'queue',
                'failed',
                $queueName,
                '*',
            ),
        );

        sort($failedKeys);

        $failedKeysNoNamespace = $this->namespaceFactory->removeNamespaceFromKeys(
            $failedKeys,
        );

        $items = array_map(
            fn (string $key) => $this->cachePool->getItem($key)->get(),
            $failedKeysNoNamespace,
        );

        /** @phpstan-ignore-next-line */
        return new QueueItemFailedCollection($items);
    }
}
