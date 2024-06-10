<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use DateTimeImmutable;

readonly class QueueItemCompletedResult
{
    public bool $hasResult;

    public QueueItemCompleted $queueItem;

    public function __construct(QueueItemCompleted|null $queueItem = null)
    {
        $this->hasResult = $queueItem !== null;

        $this->queueItem = $queueItem ?? new QueueItemCompleted(
            '',
            new QueueItemWithKey(
                '',
                '',
                '',
                '',
                new QueueItemJobCollection(
                    [new QueueItemJob(NoOp::class)],
                ),
            ),
            new DateTimeImmutable(),
        );
    }
}
