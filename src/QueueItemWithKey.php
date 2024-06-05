<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

use Spatie\Cloneable\Cloneable;

readonly class QueueItemWithKey
{
    use Cloneable;

    public static function fromQueueItem(
        string $queueName,
        string $key,
        QueueItem $queueItem,
    ): self {
        return new self(
            $queueName,
            $key,
            $queueItem->handle,
            $queueItem->name,
            $queueItem->jobs,
        );
    }

    public function __construct(
        public string $queueName,
        public string $key,
        public string $handle,
        public string $name,
        public QueueItemJobCollection $jobs,
    ) {
    }

    /** @phpstan-ignore-next-line */
    public function asArray(): array
    {
        return [
            'key' => $this->key,
            'handle' => $this->handle,
            'name' => $this->name,
            'jobs' => $this->jobs->asArray(),
        ];
    }

    public function withKey(string $key): self
    {
        return $this->with(key: $key);
    }
}
