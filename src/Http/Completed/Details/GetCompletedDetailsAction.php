<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Completed\Details;

use BuzzingPixel\Queue\Http\Routes\RequestMethod;
use BuzzingPixel\Queue\Http\Routes\Route;

class GetCompletedDetailsAction
{
    public static function createRoute(string $routePrefix): Route
    {
        return new Route(
            RequestMethod::GET,
            $routePrefix . '/completed/details/{key}',
            self::class,
        );
    }
}
