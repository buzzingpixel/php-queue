<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use Psr\Log\LoggerInterface;
use Stringable;

class NoOpLogger implements LoggerInterface
{
    /** @inheritDoc */
    public function emergency(Stringable|string $message, array $context = [])
    {
    }

    /** @inheritDoc */
    public function alert(Stringable|string $message, array $context = [])
    {
    }

    /** @inheritDoc */
    public function critical(Stringable|string $message, array $context = [])
    {
    }

    /** @inheritDoc */
    public function error(Stringable|string $message, array $context = [])
    {
    }

    /** @inheritDoc */
    public function warning(Stringable|string $message, array $context = [])
    {
    }

    /** @inheritDoc */
    public function notice(Stringable|string $message, array $context = [])
    {
    }

    /** @inheritDoc */
    public function info(Stringable|string $message, array $context = [])
    {
    }

    /** @inheritDoc */
    public function debug(Stringable|string $message, array $context = [])
    {
    }

    /** @inheritDoc */
    public function log($level, Stringable|string $message, array $context = [])
    {
    }
}
