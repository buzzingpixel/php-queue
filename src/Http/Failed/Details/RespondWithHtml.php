<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Failed\Details;

use BuzzingPixel\Queue\Http\ActiveMenuItem;
use BuzzingPixel\Queue\Http\Breadcrumbs\Breadcrumb;
use BuzzingPixel\Queue\Http\Breadcrumbs\BreadcrumbsFactory;
use BuzzingPixel\Queue\Http\Failed\Details\Retry\RetryFailedUrlFactory;
use BuzzingPixel\Queue\Http\Failed\GetFailedAction;
use BuzzingPixel\Queue\Http\HttpPath;
use BuzzingPixel\Queue\Http\LayoutVarsFactory;
use BuzzingPixel\Queue\Http\Routes\RoutesFactory;
use BuzzingPixel\Queue\QueueItemFailed;
use BuzzingPixel\Templating\TemplateEngineFactory;
use Psr\Http\Message\ResponseInterface;

readonly class RespondWithHtml
{
    public function __construct(
        private RoutesFactory $routesFactory,
        private LayoutVarsFactory $layoutVarsFactory,
        private BreadcrumbsFactory $breadcrumbsFactory,
        private RetryFailedUrlFactory $retryFailedUrlFactory,
        private TemplateEngineFactory $templateEngineFactory,
    ) {
    }

    public function respond(
        QueueItemFailed $queueItem,
        ResponseInterface $response,
    ): ResponseInterface {
        $routes = $this->routesFactory->create();

        $failedRoute = $routes->pluckClassName(
            GetFailedAction::class,
        );

        $template = $this->templateEngineFactory->create()
            ->templatePath(DetailsPath::DETAILS_INTERFACE)
            ->vars($this->layoutVarsFactory->createVars(
                'Failed Item Details',
                ActiveMenuItem::FAILED,
            ))
            ->addVar(
                'breadcrumbs',
                $this->breadcrumbsFactory->render([
                    new Breadcrumb(
                        'Failed',
                        $failedRoute->pattern,
                    ),
                    new Breadcrumb('Viewing Details'),
                ]),
            )
            ->addVar('queueItem', $queueItem)
            ->addVar('retryFailedUrl', $this->retryFailedUrlFactory->make(
                $queueItem->key,
            ))
            ->extends(HttpPath::LAYOUT_INTERFACE);

        $response->getBody()->write($template->render());

        return $response;
    }
}
