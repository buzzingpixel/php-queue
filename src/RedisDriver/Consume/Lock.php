<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver\Consume;

use BuzzingPixel\Queue\QueueConfig;
use Redis;

use function count;
use function explode;

readonly class Lock
{
    public function __construct(
        private Redis $redis,
        private QueueConfig $config,
        private EnqueuedKeys $enqueuedKeys,
    ) {
    }

    public function obtainForNext(string $queueName): LockResult
    {
        $enqueuedKeys = $this->enqueuedKeys->find($queueName);

        $this->config->logger->debug(
            'Enqueued items available: ' . $enqueuedKeys->count(),
            [
                'total' => $enqueuedKeys->count(),
                'keys' => $enqueuedKeys->enqueuedKeys,
            ],
        );

        foreach ($enqueuedKeys->enqueuedKeys as $key) {
            $lockKeyRaw = 'lock_' . $key;

            $lockObtained = $this->redis->setnx($lockKeyRaw, 'true');

            if (! $lockObtained) {
                continue;
            }

            $this->redis->expire(
                $lockKeyRaw,
                $this->config->jobsExpiresAfterSeconds,
            );

            $consumeKeyRaw = $key;

            $consumeKeyArray = explode(':', $consumeKeyRaw);

            $consumeKeySymfony = $consumeKeyArray[count($consumeKeyArray) - 1];

            $this->config->logger->debug(
                'Queue acquired lock on key ' . $consumeKeyRaw,
                ['key' => $consumeKeyRaw],
            );

            return new LockResult(
                true,
                $lockKeyRaw,
                $consumeKeyRaw,
                $consumeKeySymfony,
            );
        }

        return new LockResult();
    }

    public function release(LockResult $lockResult): void
    {
        $this->redis->del($lockResult->lockKeyRaw);

        $this->redis->del($lockResult->consumeKeyRaw);
    }
}
