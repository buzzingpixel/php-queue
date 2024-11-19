<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

readonly class QueueItemResult
{
    public bool $hasResult;

    public QueueItemWithKey $queueItem;

    public function __construct(QueueItemWithKey|null $queueItem = null)
    {
        $this->hasResult = $queueItem !== null;

        $this->queueItem = $queueItem ?? new QueueItemWithKey(
            '',
            '',
            '',
            '',
            new QueueItemJobCollection(
                [new QueueItemJob(NoOp::class)],
            ),
        );
    }
}
