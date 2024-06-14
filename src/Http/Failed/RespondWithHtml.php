<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Failed;

use BuzzingPixel\Queue\Http\ActiveMenuItem;
use BuzzingPixel\Queue\Http\Failed\Details\DetailsUrlFactory;
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
        FailedItemsResult $result,
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $template = $this->templateEngineFactory->create()
            ->templatePath(FailedPath::FAILED_INTERFACE)
            ->vars($this->layoutVarsFactory->createVars(
                'Enqueued Items',
                ActiveMenuItem::FAILED,
            ))
            ->addVar('timezone', $this->config->displayTimezone)
            ->addVar('dateFormat', $this->config->displayDateFormat)
            ->addVar('items', $result->filteredItems)
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
