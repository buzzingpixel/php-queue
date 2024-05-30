<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http;

use BuzzingPixel\Queue\Http\Css\GetCssAction;
use BuzzingPixel\Queue\Http\Enqueued\GetEnqueuedAction;
use BuzzingPixel\Queue\Http\Routes\Route;
use BuzzingPixel\Queue\Http\Routes\RoutesFactory;

use function array_merge;

readonly class LayoutVarsFactory
{
    public function __construct(private RoutesFactory $routesFactory)
    {
    }

    /**
     * @param array<mixed> $addVars
     *
     * @return array<mixed>
     */
    public function createVars(
        string $pageTitle,
        ActiveMenuItem|null $activeMenuItem = null,
        array $addVars = [],
    ): array {
        $routes = $this->routesFactory->create();

        $cssRoute = $routes->filter(
            static fn (
                Route $route,
            ) => $route->class === GetCssAction::class,
        )->first();

        $enqueuedRoute = $routes->filter(
            static fn (
                Route $route,
            ) => $route->class === GetEnqueuedAction::class,
        )->first();

        return array_merge(
            [
                'cssUri' => $cssRoute->pattern,
                'pageTitle' => $pageTitle,
                'sidebarLinks' => new SidebarLinksCollection([
                    new SidebarLink(
                        'Enqueued',
                        $enqueuedRoute->pattern,
                        $activeMenuItem === ActiveMenuItem::ENQUEUED,
                    ),
                    new SidebarLink(
                        'TODO',
                        '/todo',
                        $activeMenuItem === ActiveMenuItem::TODO,
                    ),
                ]),
            ],
            $addVars,
        );
    }
}
