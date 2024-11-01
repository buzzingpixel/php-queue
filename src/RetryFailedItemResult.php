<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

readonly class RetryFailedItemResult
{
    public function __construct(
        public bool $wasSuccessful,
    ) {
    }

    /** @return bool[] */
    public function asArray(): array
    {
        return [
            'wasSuccessful' => $this->wasSuccessful,
        ];
    }
}
