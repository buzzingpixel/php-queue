<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Completed;

use function array_map;
use function array_values;

readonly class Tabs
{
    /** @var Tab[] */
    public array $items;

    /** @param Tab[] $items */
    public function __construct(array $items)
    {
        $this->items = array_values(array_map(
            static fn (Tab $t) => $t,
            $items,
        ));
    }
}
