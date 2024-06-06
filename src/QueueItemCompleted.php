<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use DateTimeImmutable;
use DateTimeInterface;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

readonly class QueueItemCompleted
{
    public function __construct(
        public string $key,
        public QueueItemWithKey $queueItem,
        public DateTimeImmutable $date,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function asArray(): array
    {
        return [
            'key' => $this->key,
            'queueItem' => $this->queueItem->asArray(),
            'date' => $this->date->format(DateTimeInterface::ATOM),
        ];
    }
}
