<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

class QueueItemWithKey
{
    public static function fromQueueItem(
        string $key,
        QueueItem $queueItem,
    ): self {
        return new self(
            $key,
            $queueItem->handle,
            $queueItem->name,
            $queueItem->jobs,
        );
    }

    public function __construct(
        public string $key,
        public string $handle,
        public string $name,
        public QueueItemJobCollection $jobs,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function asArray(): array
    {
        return [
            'key' => $this->key,
            'handle' => $this->handle,
            'name' => $this->name,
            'jobs' => $this->jobs->asArray(),
        ];
    }
}
