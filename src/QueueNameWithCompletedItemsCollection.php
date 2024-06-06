<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use function array_map;
use function array_values;
use function count;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

readonly class QueueNameWithCompletedItemsCollection
{
    /** @var QueueNameWithCompletedItems[] */
    public array $items;

    /** @param QueueNameWithCompletedItems[] $items */
    public function __construct(array $items = [])
    {
        $this->items = array_values(array_map(
            static fn (QueueNameWithCompletedItems $i) => $i,
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

    public function flattenItems(): QueueItemWithKeyCollection
    {
        $items = [];

        $this->map(static function (QueueNameWithCompletedItems $item) use (
            &$items,
        ): void {
            $item->items->map(
                static function (QueueItemCompleted $item) use (
                    &$items,
                ): void {
                    $items[] = $item->queueItem;
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
            static function (
                QueueNameWithCompletedItems $queue,
            ): array {
                return $queue->asArray();
            },
        );
    }
}
