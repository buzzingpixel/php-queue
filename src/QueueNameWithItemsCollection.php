<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use function array_map;
use function array_values;
use function count;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

readonly class QueueNameWithItemsCollection
{
    /** @var QueueNameWithItems[] */
    public array $queues;

    /** @param QueueNameWithItems[] $queues */
    public function __construct(array $queues = [])
    {
        $this->queues = array_values(array_map(
            static fn (QueueNameWithItems $q) => $q,
            $queues,
        ));
    }

    public function countQueues(): int
    {
        return count($this->queues);
    }

    /** @phpstan-ignore-next-line */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->queues);
    }

    public function flattenItems(): QueueItemWithKeyCollection
    {
        $items = [];

        $this->map(static function (QueueNameWithItems $queue) use (
            &$items,
        ): void {
            $queue->items->map(
                static function (QueueItemWithKey $item) use (
                    &$items,
                ): void {
                    $items[] = $item;
                },
            );
        });

        return new QueueItemWithKeyCollection($items);
    }

    /** @phpstan-ignore-next-line */
    public function mapAllItems(callable $callback): array
    {
        return $this->flattenItems()->map($callback);
    }

    public function countTotalItems(): int
    {
        return $this->flattenItems()->count();
    }

    /** @phpstan-ignore-next-line */
    public function asArray(): array
    {
        return $this->map(
            static function (QueueNameWithItems $queue): array {
                return $queue->asArray();
            },
        );
    }
}
