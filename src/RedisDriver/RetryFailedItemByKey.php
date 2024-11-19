<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver;

use BuzzingPixel\Queue\QueueItem;
use BuzzingPixel\Queue\RetryFailedItemResult;
use Symfony\Component\Cache\Adapter\RedisAdapter;

readonly class RetryFailedItemByKey
{
    public function __construct(
        private Enqueue $enqueue,
        private RedisAdapter $cachePool,
        private FailedItemByKey $failedItemByKey,
    ) {
    }

    public function retry(string $key): RetryFailedItemResult
    {
        $itemResult = $this->failedItemByKey->find($key);

        if (! $itemResult->hasResult) {
            return new RetryFailedItemResult(false);
        }

        $queueItem = $itemResult->queueItem->queueItem;

        $status = $this->enqueue->item(
            new QueueItem(
                $queueItem->handle,
                $queueItem->name,
                $queueItem->jobs,
            ),
            $queueItem->queueName,
        );

        if (! $status) {
            return new RetryFailedItemResult(false);
        }

        $failedItem = $itemResult->queueItem->withRetried();

        $cacheItem = $this->cachePool->getItem(
            $itemResult->queueItem->key,
        )->set($failedItem);

        $this->cachePool->save($cacheItem);

        return new RetryFailedItemResult(true);
    }
}
