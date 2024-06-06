<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Routes;

use BuzzingPixel\Queue\Http\Completed\Details\GetCompletedDetailsAction;
use BuzzingPixel\Queue\Http\Completed\GetCompletedAction;
use BuzzingPixel\Queue\Http\Css\GetCssAction;
use BuzzingPixel\Queue\Http\Enqueued\Details\GetEnqueuedDetailsAction;
use BuzzingPixel\Queue\Http\Enqueued\GetEnqueuedAction;

readonly class RoutesFactory
{
    public function __construct(
        private string $routePrefix = '/buzzingpixel-queue',
    ) {
    }

    public function create(): RouteCollection
    {
        return new RouteCollection([
            GetCssAction::createRoute($this->routePrefix),
            GetEnqueuedAction::createRoute($this->routePrefix),
            GetEnqueuedDetailsAction::createRoute(
                $this->routePrefix,
            ),
            GetCompletedAction::createRoute(
                $this->routePrefix,
            ),
            GetCompletedDetailsAction::createRoute(
                $this->routePrefix,
            ),
        ]);
    }
}
