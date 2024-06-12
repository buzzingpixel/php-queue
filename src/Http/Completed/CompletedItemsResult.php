<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Completed;

use BuzzingPixel\Queue\QueueNameWithCompletedItemsCollection;

readonly class CompletedItemsResult
{
    public function __construct(
        public QueueNameWithCompletedItemsCollection $allItems,
        public QueueNameWithCompletedItemsCollection $filteredItems,
    ) {
    }
}
