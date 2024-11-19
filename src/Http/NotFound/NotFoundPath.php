<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\NotFound;

class NotFoundPath
{
    public const PATH = __DIR__;

    public const NOT_FOUND_INTERFACE = self::PATH . '/NotFoundInterface.phtml';
}
