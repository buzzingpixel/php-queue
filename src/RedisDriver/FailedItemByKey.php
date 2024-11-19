<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver;

use BuzzingPixel\Queue\QueueItemFailed;
use BuzzingPixel\Queue\QueueItemFailedResult;
use Symfony\Component\Cache\Adapter\RedisAdapter;

readonly class FailedItemByKey
{
    public function __construct(private RedisAdapter $cachePool)
    {
    }

    public function find(string $key): QueueItemFailedResult
    {
        $result = $this->cachePool->getItem($key);

        if (! $result->isHit()) {
            return new QueueItemFailedResult();
        }

        $item = $result->get();

        if (! ($item instanceof QueueItemFailed)) {
            return new QueueItemFailedResult();
        }

        return new QueueItemFailedResult($item);
    }
}
