<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use RuntimeException;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

class QueueItem
{
    public function __construct(
        public string $handle,
        public string $name,
        public QueueItemJobCollection $jobs,
    ) {
        if ($this->handle === '') {
            throw new RuntimeException('$handle must be provided');
        }
    }
}
