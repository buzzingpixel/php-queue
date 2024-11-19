<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use function in_array;

readonly class QueueNamesDefault implements QueueNames
{
    /** @inheritDoc */
    public function getAvailableQueues(): array
    {
        return ['default'];
    }

    public function nameIsValid(string $queueName): bool
    {
        return in_array(
            $queueName,
            $this->getAvailableQueues(),
            true,
        );
    }
}
