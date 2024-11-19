<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use DateTimeImmutable;
use DateTimeInterface;
use Spatie\Cloneable\Cloneable;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

readonly class QueueItemFailed
{
    use Cloneable;

    public function __construct(
        public string $key,
        public string $message,
        public int $code,
        public string $file,
        public int $line,
        public string $trace,
        public QueueItemWithKey $queueItem,
        public DateTimeImmutable $date,
        public bool $retried = false,
    ) {
    }

    public function withRetried(bool $retried = true): QueueItemFailed
    {
        return $this->with(retried: $retried);
    }

    /** @phpstan-ignore-next-line */
    public function asArray(): array
    {
        return [
            'key' => $this->key,
            'message' => $this->message,
            'code' => $this->code,
            'file' => $this->file,
            'line' => $this->line,
            'trace' => $this->trace,
            'queueItem' => $this->queueItem->asArray(),
            'retried' => $this->retried,
            'date' => $this->date->format(DateTimeInterface::ATOM),
        ];
    }
}
