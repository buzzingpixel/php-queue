<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Routes;

readonly class RoutesFactory
{
    public function __construct(
        private string $routePrefix = '/buzzingpixel-queue',
    ) {
    }

    public function create(): RouteCollection
    {
        return new RouteCollection([]);
    }
}
