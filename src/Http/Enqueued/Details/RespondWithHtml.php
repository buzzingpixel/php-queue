<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Enqueued\Details;

use BuzzingPixel\Queue\Http\ActiveMenuItem;
use BuzzingPixel\Queue\Http\Breadcrumbs\Breadcrumb;
use BuzzingPixel\Queue\Http\Breadcrumbs\BreadcrumbsFactory;
use BuzzingPixel\Queue\Http\Enqueued\GetEnqueuedAction;
use BuzzingPixel\Queue\Http\HttpPath;
use BuzzingPixel\Queue\Http\LayoutVarsFactory;
use BuzzingPixel\Queue\Http\Routes\RoutesFactory;
use BuzzingPixel\Queue\QueueItemWithKey;
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
        QueueItemWithKey $queueItem,
        ResponseInterface $response,
    ): ResponseInterface {
        $routes = $this->routesFactory->create();

        $enqueuedRoute = $routes->pluckClassName(
            GetEnqueuedAction::class,
        );

        $template = $this->templateEngineFactory->create()
            ->templatePath(DetailsPath::DETAILS_INTERFACE)
            ->vars($this->layoutVarsFactory->createVars(
                'Enqueued Item Details',
                ActiveMenuItem::ENQUEUED,
            ))
            ->addVar(
                'breadcrumbs',
                $this->breadcrumbsFactory->render([
                    new Breadcrumb(
                        'Enqueued',
                        $enqueuedRoute->pattern,
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
