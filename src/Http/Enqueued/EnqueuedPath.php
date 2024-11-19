<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Enqueued;

readonly class EnqueuedPath
{
    public const PATH = __DIR__;

    public const ENQUEUED_INTERFACE = self::PATH . '/EnqueuedInterface.phtml';
}
