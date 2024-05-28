<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http;

class HttpPath
{
    public const PATH = __DIR__;

    public const LAYOUT_INTERFACE = self::PATH . '/LayoutInterface.phtml';

    public const SIDEBAR_PARTIAL = self::PATH . '/LayoutSidebar.phtml';
}
