<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Completed\Details;

use BuzzingPixel\Queue\Http\ActiveMenuItem;
use BuzzingPixel\Queue\Http\Breadcrumbs\Breadcrumb;
use BuzzingPixel\Queue\Http\Breadcrumbs\BreadcrumbsFactory;
use BuzzingPixel\Queue\Http\Completed\GetCompletedAction;
use BuzzingPixel\Queue\Http\HttpPath;
use BuzzingPixel\Queue\Http\LayoutVarsFactory;
use BuzzingPixel\Queue\Http\Routes\Route;
use BuzzingPixel\Queue\Http\Routes\RoutesFactory;
use BuzzingPixel\Queue\QueueItemCompleted;
use BuzzingPixel\Templating\TemplateEngineFactory;
use Psr\Http\Message\ResponseInterface;

readonly class RespondWithHtml
{
    public function __construct(
        private RoutesFactory $routesFactory,
        private LayoutVarsFactory $layoutVarsFactory,
        private BreadcrumbsFactory $breadcrumbsFactory,
        private TemplateEngineFactory $templateEngineFactory,
    ) {
    }

    public function respond(
        QueueItemCompleted $queueItem,
        ResponseInterface $response,
    ): ResponseInterface {
        $routes = $this->routesFactory->create();

        $completedRoute = $routes->filter(
            static fn (
                Route $route,
            ) => $route->class === GetCompletedAction::class,
        )->first();

        $template = $this->templateEngineFactory->create()
            ->templatePath(DetailsPath::DETAILS_INTERFACE)
            ->vars($this->layoutVarsFactory->createVars(
                'Completed Item Details',
                ActiveMenuItem::COMPLETED,
            ))
            ->addVar(
                'breadcrumbs',
                $this->breadcrumbsFactory->render([
                    new Breadcrumb(
                        'Completed',
                        $completedRoute->pattern,
                    ),
                    new Breadcrumb('Viewing Details'),
                ]),
            )
            ->addVar('queueItem', $queueItem)
            ->extends(HttpPath::LAYOUT_INTERFACE);

        $response->getBody()->write($template->render());

        return $response;
    }
}
