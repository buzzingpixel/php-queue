<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Failed;

use BuzzingPixel\Queue\QueueNameWithFailedItemsCollection;

readonly class FailedItemsResult
{
    public function __construct(
        public QueueNameWithFailedItemsCollection $allItems,
        public QueueNameWithFailedItemsCollection $filteredItems,
    ) {
    }
}
