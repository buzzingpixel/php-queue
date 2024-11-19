<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Failed\Details\Retry;

use BuzzingPixel\Queue\Http\Routes\RoutesFactory;

use function str_replace;

readonly class RetryFailedUrlFactory
{
    public function __construct(private RoutesFactory $routesFactory)
    {
    }

    public function make(string $key): string
    {
        $routes = $this->routesFactory->create();

        $retryRoute = $routes->pluckClassName(
            PostRetryFailedAction::class,
        );

        return str_replace(
            '{key}',
            $key,
            $retryRoute->pattern,
        );
    }
}
