<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Enqueued;

use BuzzingPixel\Queue\Http\ActiveMenuItem;
use BuzzingPixel\Queue\Http\Enqueued\Details\DetailsUrlFactory;
use BuzzingPixel\Queue\Http\HttpPath;
use BuzzingPixel\Queue\Http\LayoutVarsFactory;
use BuzzingPixel\Queue\Http\Tabs\QueueHeadingTitleAndTabBuilder;
use BuzzingPixel\Templating\TemplateEngineFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly class RespondWithHtml
{
    public function __construct(
        private DetailsUrlFactory $detailsUrlFactory,
        private LayoutVarsFactory $layoutVarsFactory,
        private TemplateEngineFactory $templateEngineFactory,
        private QueueHeadingTitleAndTabBuilder $headingBuilder,
    ) {
    }

    public function respond(
        EnqueuedItemsResult $result,
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $template = $this->templateEngineFactory->create()
            ->templatePath(EnqueuedPath::ENQUEUED_INTERFACE)
            ->vars($this->layoutVarsFactory->createVars(
                'Enqueued Items',
                ActiveMenuItem::ENQUEUED,
            ))
            ->addVar('queues', $result->filteredItems)
            ->addVar('detailsUrlFactory', $this->detailsUrlFactory)
            ->addVar('heading', $this->headingBuilder->render(
                $result->allItems,
                $request,
                'Enqueued Items',
            ))
            ->extends(HttpPath::LAYOUT_INTERFACE);

        $response->getBody()->write($template->render());

        return $response;
    }
}
