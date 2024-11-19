<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use function array_map;
use function array_values;
use function count;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

readonly class QueueItemWithKeyCollection
{
    /** @var QueueItemWithKey[] */
    public array $queueItems;

    /** @param QueueItemWithKey[] $queueItems */
    public function __construct(array $queueItems = [])
    {
        $this->queueItems = array_values(array_map(
            static fn (QueueItemWithKey $i) => $i,
            $queueItems,
        ));
    }

    /** @phpstan-ignore-next-line */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->queueItems);
    }

    /** @phpstan-ignore-next-line */
    public function asArray(): array
    {
        return $this->map(
            static fn (QueueItemWithKey $item) => $item->asArray(),
        );
    }

    public function count(): int
    {
        return count($this->queueItems);
    }
}
