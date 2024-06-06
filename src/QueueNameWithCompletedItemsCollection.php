<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use function array_map;
use function array_values;

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
}
