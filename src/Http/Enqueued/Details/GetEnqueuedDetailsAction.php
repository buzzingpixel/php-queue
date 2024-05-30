<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Enqueued\Details;

use BuzzingPixel\Queue\Http\Routes\RequestMethod;
use BuzzingPixel\Queue\Http\Routes\Route;

readonly class GetEnqueuedDetailsAction
{
    public static function createRoute(string $routePrefix): Route
    {
        return new Route(
            RequestMethod::GET,
            $routePrefix . '/enqueued/details/{key}',
            self::class,
        );
    }
}
