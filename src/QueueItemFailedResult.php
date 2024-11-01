<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use DateTimeImmutable;

readonly class QueueItemFailedResult
{
    public bool $hasResult;

    public QueueItemFailed $queueItem;

    public function __construct(QueueItemFailed|null $queueItem = null)
    {
        $this->hasResult = $queueItem !== null;

        $this->queueItem = $queueItem ?? new QueueItemFailed(
            '',
            '',
            0,
            '',
            0,
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
