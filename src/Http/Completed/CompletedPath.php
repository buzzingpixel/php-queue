<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Completed;

readonly class CompletedPath
{
    public const PATH = __DIR__;

    public const COMPLETED_INTERFACE = self::PATH . '/CompletedInterface.phtml';
}
