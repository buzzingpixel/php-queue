<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use function array_filter;
use function array_map;
use function array_values;
use function count;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

readonly class QueueNameWithFailedItemsCollection
{
    /** @var QueueNameWithFailedItems[] */
    public array $items;

    /** @param QueueNameWithFailedItems[] $items */
    public function __construct(array $items = [])
    {
        $this->items = array_values(array_map(
            static fn (QueueNameWithFailedItems $i) => $i,
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

    public function flattenItems(): QueueItemFailedCollection
    {
        $items = [];

        $this->map(
            static function (
                QueueNameWithFailedItems $item,
            ) use (&$items): void {
                $item->items->map(
                    static function (
                        QueueItemFailed $item,
                    ) use (&$items): void {
                        $items[] = $item;
                    },
                );
            },
        );

        return new QueueItemFailedCollection($items);
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

    public function filter(callable $callback): self
    {
        return new self(array_filter(
            $this->items,
            $callback,
        ));
    }

    public function filterWhereQueueNameIs(string $queueName): self
    {
        return $this->filter(static fn (
            QueueNameWithFailedItems $i,
        ) => $i->queueName === $queueName);
    }

    /** @phpstan-ignore-next-line */
    public function asArray(): array
    {
        return $this->map(
            static function (
                QueueNameWithFailedItems $queue,
            ): array {
                return $queue->asArray();
            },
        );
    }
}
