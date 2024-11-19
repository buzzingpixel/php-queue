<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Completed;

use BuzzingPixel\Queue\Http\ActiveMenuItem;
use BuzzingPixel\Queue\Http\Completed\Details\DetailsUrlFactory;
use BuzzingPixel\Queue\Http\HttpPath;
use BuzzingPixel\Queue\Http\LayoutVarsFactory;
use BuzzingPixel\Queue\Http\Tabs\QueueHeadingTitleAndTabBuilder;
use BuzzingPixel\Queue\QueueConfig;
use BuzzingPixel\Templating\TemplateEngineFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly class RespondWithHtml
{
    public function __construct(
        private QueueConfig $config,
        private DetailsUrlFactory $detailsUrlFactory,
        private LayoutVarsFactory $layoutVarsFactory,
        private TemplateEngineFactory $templateEngineFactory,
        private QueueHeadingTitleAndTabBuilder $headingBuilder,
    ) {
    }

    public function respond(
        CompletedItemsResult $result,
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $template = $this->templateEngineFactory->create()
            ->templatePath(CompletedPath::COMPLETED_INTERFACE)
            ->vars($this->layoutVarsFactory->createVars(
                'Enqueued Items',
                ActiveMenuItem::COMPLETED,
            ))
            ->addVar('timezone', $this->config->displayTimezone)
            ->addVar('items', $result->filteredItems)
            ->addVar('dateFormat', $this->config->displayDateFormat)
            ->addVar('detailsUrlFactory', $this->detailsUrlFactory)
            ->addVar('heading', $this->headingBuilder->render(
                $result->allItems,
                $request,
                'Completed Items',
            ))
            ->extends(HttpPath::LAYOUT_INTERFACE);

        $response->getBody()->write($template->render());

        return $response;
    }
}
