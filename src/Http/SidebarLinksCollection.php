<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http;

use function array_map;
use function array_values;

readonly class SidebarLinksCollection
{
    /** @var SidebarLink[] */
    public array $links;

    /** @param SidebarLink[] $links */
    public function __construct(array $links)
    {
        $this->links = array_values(array_map(
            static fn (SidebarLink $link) => $link,
            $links,
        ));
    }

    /** @return array<mixed> */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->links);
    }
}
