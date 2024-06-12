<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Tabs;

readonly class TabsPath
{
    public const PATH = __DIR__;

    public const TABS_INTERFACE = self::PATH . '/TabsInterface.phtml';

    public const TABS_WITH_TITLE_INTERFACE = self::PATH . '/TabsWithTitleInterface.phtml';
}
