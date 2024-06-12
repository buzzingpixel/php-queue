<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Enqueued;

use BuzzingPixel\Queue\QueueNameWithItemsCollection;

readonly class EnqueuedItemsResult
{
    public function __construct(
        public QueueNameWithItemsCollection $allItems,
        public QueueNameWithItemsCollection $filteredItems,
    ) {
    }
}
