<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

readonly class QueueNameWithItems
{
    public function __construct(
        public string $queueName,
        public QueueItemWithKeyCollection $items,
    ) {
    }

    public function count(): int
    {
        return $this->items->count();
    }

    /** @phpstan-ignore-next-line */
    public function asArray(): array
    {
        return [
            'queueName' => $this->queueName,
            'items' => $this->items->asArray(),
        ];
    }
}
