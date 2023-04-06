<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

interface QueueHandler
{
    public const JOBS_EXPIRES_AFTER_SECONDS = 3600;

    public function jobsExpiresAfterSeconds(): int;

    public function enqueue(
        QueueItem $queueItem,
        string $queueName = 'default',
    ): bool;

    public function consumeNext(
        string $queueName = 'default',
    ): void;
}
