<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver;

use BuzzingPixel\Queue\QueueConfig;
use BuzzingPixel\Queue\QueueItem;
use Ramsey\Uuid\UuidFactory;
use RuntimeException;
use Symfony\Component\Cache\Adapter\RedisAdapter;

use function implode;

readonly class Enqueue
{
    public function __construct(
        private QueueConfig $config,
        private RedisAdapter $cachePool,
        private UuidFactory $uuidFactory,
    ) {
    }

    public function item(
        QueueItem $queueItem,
        string $queueName,
    ): bool {
        if (! $this->config->queueNames->nameIsValid($queueName)) {
            throw new RuntimeException(
                'Invalid queue name ' . $queueName,
            );
        }

        $uid = $this->uuidFactory->uuid4()->toString();

        $handle = $queueItem->handle;

        $key = implode('_', [
            'queue',
            $queueName,
            $this->config->clock->now()->getTimestamp(),
            $handle,
            $uid,
        ]);

        $this->config->logger->debug(
            'Enqueueing item',
            [
                'handle' => $queueItem->handle,
                'name' => $queueItem->name,
            ],
        );

        return $this->cachePool->save(
            $this->cachePool->getItem($key)->set($queueItem),
        );
    }
}
