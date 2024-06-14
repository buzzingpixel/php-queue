<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Failed;

class FailedPath
{
    public const PATH = __DIR__;

    public const FAILED_INTERFACE = self::PATH . '/FailedInterface.phtml';
}
