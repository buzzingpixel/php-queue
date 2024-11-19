<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Failed\Details;

use BuzzingPixel\Queue\Http\Routes\RoutesFactory;

use function str_replace;

readonly class DetailsUrlFactory
{
    public function __construct(private RoutesFactory $routesFactory)
    {
    }

    public function make(string $key): string
    {
        $routes = $this->routesFactory->create();

        $detailsRoute = $routes->pluckClassName(
            GetFailedDetailsAction::class,
        );

        return str_replace(
            '{key}',
            $key,
            $detailsRoute->pattern,
        );
    }
}
