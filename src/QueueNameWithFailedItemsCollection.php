<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use function array_map;
use function array_values;

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
}
