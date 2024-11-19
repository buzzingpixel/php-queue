<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use RuntimeException;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

readonly class QueueItem
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

    /** @phpstan-ignore-next-line */
    public function asArray(): array
    {
        return [
            'handle' => $this->handle,
            'name' => $this->name,
            'jobs' => $this->jobs->asArray(),
        ];
    }
}
