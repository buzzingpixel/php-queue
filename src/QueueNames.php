<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

interface QueueNames
{
    /** @return string[] */
    public function getAvailableQueues(): array;

    public function nameIsValid(string $queueName): bool;
}
