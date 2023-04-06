<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification

class QueueItemJob
{
    /**
     * @param class-string $class
     * @param mixed[]      $context Must be `json_encode`-able
     */
    public function __construct(
        public string $class,
        public string $method = '__invoke',
        public array $context = [],
    ) {
    }
}
