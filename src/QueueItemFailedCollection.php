<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use function array_map;
use function array_values;
use function count;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

readonly class QueueItemFailedCollection
{
    /** @var QueueItemFailed[] */
    public array $items;

    /** @param QueueItemFailed[] $items */
    public function __construct(array $items)
    {
        $this->items = array_values(array_map(
            static fn (QueueItemFailed $i) => $i,
            $items,
        ));
    }

    public function count(): int
    {
        return count($this->items);
    }

    /** @phpstan-ignore-next-line */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->items);
    }

    /** @phpstan-ignore-next-line */
    public function asArray(): array
    {
        return $this->map(
            static fn (QueueItemFailed $item) => $item->asArray(),
        );
    }
}
