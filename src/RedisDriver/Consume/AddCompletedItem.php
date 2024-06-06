<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver\Consume;

use BuzzingPixel\Queue\QueueConfig;
use BuzzingPixel\Queue\QueueItemWithKey;
use BuzzingPixel\Queue\RedisDriver\ExtractUuid;
use Symfony\Component\Cache\Adapter\RedisAdapter;

use function implode;

readonly class AddCompletedItem
{
    public function __construct(
        private QueueConfig $config,
        private RedisAdapter $cachePool,
        private ExtractUuid $extractUuid,
    ) {
    }

    public function add(QueueItemWithKey $queueItem): bool
    {
        $queueItem = $queueItem->withKey(implode('_', [
            'queue',
            'completed',
            $queueItem->queueName,
            $this->config->clock->now()->getTimestamp(),
            $queueItem->handle,
            $this->extractUuid->fromKey($queueItem->key),
        ]));

        $this->config->logger->debug(
            'Saving completed item',
            [
                'handle' => $queueItem->handle,
                'name' => $queueItem->name,
            ],
        );

        return $this->cachePool->save(
            $this->cachePool->getItem($queueItem->key)
                ->set($queueItem)
                ->expiresAfter(
                    $this->config->completedItemsExpireAfterSeconds,
                ),
        );
    }
}
