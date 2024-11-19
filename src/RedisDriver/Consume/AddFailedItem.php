<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver\Consume;

use BuzzingPixel\Queue\QueueConfig;
use BuzzingPixel\Queue\QueueItemFailed;
use BuzzingPixel\Queue\QueueItemWithKey;
use BuzzingPixel\Queue\RedisDriver\ExtractUuid;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Throwable;

use function implode;

readonly class AddFailedItem
{
    public function __construct(
        private QueueConfig $config,
        private RedisAdapter $cachePool,
        private ExtractUuid $extractUuid,
    ) {
    }

    public function add(
        QueueItemWithKey $queueItem,
        Throwable $exception,
    ): bool {
        $dateTime = $this->config->clock->now();

        $failedItem = new QueueItemFailed(
            implode('_', [
                'queue',
                'failed',
                $queueItem->queueName,
                $dateTime->getTimestamp(),
                $queueItem->handle,
                $this->extractUuid->fromKey($queueItem->key),
            ]),
            $exception->getMessage(),
            $exception->getCode(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString(),
            $queueItem,
            $dateTime,
        );

        $this->config->logger->debug(
            'Saving failed item',
            [
                'handle' => $queueItem->handle,
                'name' => $queueItem->name,
            ],
        );

        return $this->cachePool->save(
            $this->cachePool->getItem($failedItem->key)
                ->set($failedItem)
                ->expiresAfter(
                    $this->config->failedItemsExpireAfterSeconds,
                ),
        );
    }
}
