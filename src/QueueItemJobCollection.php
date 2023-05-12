<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use RuntimeException;

use function array_map;
use function array_values;
use function count;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

class QueueItemJobCollection
{
    /** @var QueueItemJob[] $queueItemJobs */
    public array $queueItemJobs;

    /** @param QueueItemJob[] $queueItemJobs */
    public function __construct(array $queueItemJobs)
    {
        if (count($queueItemJobs) < 1) {
            throw new RuntimeException(
                'At least 1 job must be provided',
            );
        }

        $this->queueItemJobs = array_values(array_map(
            static fn (QueueItemJob $q) => $q,
            $queueItemJobs,
        ));
    }

    /** @phpstan-ignore-next-line */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->queueItemJobs);
    }

    /** @phpstan-ignore-next-line */
    public function asArray(): array
    {
        return $this->map(
            static fn (QueueItemJob $job) => $job->asArray(),
        );
    }
}
