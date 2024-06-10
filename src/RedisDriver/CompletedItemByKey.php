<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver;

use BuzzingPixel\Queue\QueueItemCompleted;
use BuzzingPixel\Queue\QueueItemCompletedResult;
use Symfony\Component\Cache\Adapter\RedisAdapter;

readonly class CompletedItemByKey
{
    public function __construct(private RedisAdapter $cachePool)
    {
    }

    public function find(string $key): QueueItemCompletedResult
    {
        $result = $this->cachePool->getItem($key);

        if (! $result->isHit()) {
            return new QueueItemCompletedResult();
        }

        $item = $result->get();

        if (! ($item instanceof QueueItemCompleted)) {
            return new QueueItemCompletedResult();
        }

        return new QueueItemCompletedResult($item);
    }
}
