<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Completed;

use BuzzingPixel\Queue\Http\ActiveMenuItem;
use BuzzingPixel\Queue\Http\Completed\Details\DetailsUrlFactory;
use BuzzingPixel\Queue\Http\HttpPath;
use BuzzingPixel\Queue\Http\LayoutVarsFactory;
use BuzzingPixel\Queue\QueueConfig;
use BuzzingPixel\Queue\QueueNameWithCompletedItemsCollection;
use BuzzingPixel\Templating\TemplateEngineFactory;
use Psr\Http\Message\ResponseInterface;

readonly class RespondWithHtml
{
    public function __construct(
        private QueueConfig $config,
        private DetailsUrlFactory $detailsUrlFactory,
        private LayoutVarsFactory $layoutVarsFactory,
        private TemplateEngineFactory $templateEngineFactory,
    ) {
    }

    public function respond(
        QueueNameWithCompletedItemsCollection $items,
        ResponseInterface $response,
    ): ResponseInterface {
        $template = $this->templateEngineFactory->create()
            ->templatePath(CompletedPath::COMPLETED_INTERFACE)
            ->vars($this->layoutVarsFactory->createVars(
                'Enqueued Items',
                ActiveMenuItem::COMPLETED,
            ))
            ->addVar('timezone', $this->config->displayTimezone)
            ->addVar('items', $items)
            ->addVar('dateFormat', $this->config->displayDateFormat)
            ->addVar('detailsUrlFactory', $this->detailsUrlFactory)
            ->extends(HttpPath::LAYOUT_INTERFACE);

        $response->getBody()->write($template->render());

        return $response;
    }
}
