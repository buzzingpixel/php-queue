<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver;

use BuzzingPixel\Queue\QueueItem;
use BuzzingPixel\Queue\QueueItemResult;
use BuzzingPixel\Queue\QueueItemWithKey;
use Symfony\Component\Cache\Adapter\RedisAdapter;

use function explode;

readonly class EnqueuedItemByKey
{
    public function __construct(private RedisAdapter $cachePool)
    {
    }

    public function find(string $key): QueueItemResult
    {
        $result = $this->cachePool->getItem($key);

        if (! $result->isHit()) {
            return new QueueItemResult();
        }

        $queueItem = $result->get();

        if (! ($queueItem instanceof QueueItem)) {
            return new QueueItemResult();
        }

        $keyParts = explode('_', $key);

        $queueName = $keyParts[1];

        return new QueueItemResult(QueueItemWithKey::fromQueueItem(
            $queueName,
            $key,
            $queueItem,
        ));
    }
}
