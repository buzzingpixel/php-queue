<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http;

use BuzzingPixel\Queue\Http\Completed\GetCompletedAction;
use BuzzingPixel\Queue\Http\Css\GetCssAction;
use BuzzingPixel\Queue\Http\Enqueued\GetEnqueuedAction;
use BuzzingPixel\Queue\Http\Failed\GetFailedAction;
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

        $cssRoute = $routes->pluckClassName(
            GetCssAction::class,
        );

        $enqueuedRoute = $routes->pluckClassName(
            GetEnqueuedAction::class,
        );

        $completedRoute = $routes->pluckClassName(
            GetCompletedAction::class,
        );

        $failedRoute = $routes->pluckClassName(
            GetFailedAction::class,
        );

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
                        'Completed',
                        $completedRoute->pattern,
                        $activeMenuItem === ActiveMenuItem::COMPLETED,
                    ),
                    new SidebarLink(
                        'Failed',
                        $failedRoute->pattern,
                        $activeMenuItem === ActiveMenuItem::FAILED,
                    ),
                ]),
            ],
            $addVars,
        );
    }
}
