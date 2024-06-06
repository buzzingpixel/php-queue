<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

readonly class QueueItemFailed
{
    public function __construct(
        public string $key,
        public string $message,
        public int $code,
        public string $file,
        public int $line,
        public string $trace,
        public QueueItemWithKey $queueItem,
    ) {
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
            'queueItem' => $this->queueItem->asArray(),
        ];
    }
}
