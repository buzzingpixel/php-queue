<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Breadcrumbs;

use BuzzingPixel\Queue\Http\Enqueued\GetEnqueuedAction;
use BuzzingPixel\Queue\Http\Routes\RoutesFactory;
use BuzzingPixel\Templating\TemplateEngineFactory;

use function array_map;
use function array_values;

readonly class BreadcrumbsFactory
{
    public function __construct(
        private RoutesFactory $routesFactory,
        private TemplateEngineFactory $templateEngineFactory,
    ) {
    }

    /** @param Breadcrumb[] $breadcrumbs */
    public function render(array $breadcrumbs): string
    {
        $routes = $this->routesFactory->create();

        $enqueuedRoute = $routes->pluckClassName(
            GetEnqueuedAction::class,
        );

        $breadcrumbs = array_values(array_map(
            static fn (Breadcrumb $b) => $b,
            $breadcrumbs,
        ));

        $template = $this->templateEngineFactory->create()
            ->templatePath(
                BreadcrumbsPath::BREADCRUMBS_INTERFACE,
            )
            ->addVar('homeUrl', $enqueuedRoute->pattern)
            ->addVar('breadcrumbs', $breadcrumbs);

        return $template->render();
    }
}
