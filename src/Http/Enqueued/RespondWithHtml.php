<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Enqueued;

use BuzzingPixel\Queue\Http\ActiveMenuItem;
use BuzzingPixel\Queue\Http\Enqueued\Details\DetailsUrlFactory;
use BuzzingPixel\Queue\Http\HttpPath;
use BuzzingPixel\Queue\Http\LayoutVarsFactory;
use BuzzingPixel\Queue\QueueNameWithItemsCollection;
use BuzzingPixel\Templating\TemplateEngineFactory;
use Psr\Http\Message\ResponseInterface;

readonly class RespondWithHtml
{
    public function __construct(
        private DetailsUrlFactory $detailsUrlFactory,
        private LayoutVarsFactory $layoutVarsFactory,
        private TemplateEngineFactory $templateEngineFactory,
    ) {
    }

    public function respond(
        QueueNameWithItemsCollection $queues,
        ResponseInterface $response,
    ): ResponseInterface {
        $template = $this->templateEngineFactory->create()
            ->templatePath(EnqueuedPath::ENQUEUED_INTERFACE)
            ->vars($this->layoutVarsFactory->createVars(
                'Enqueued Items',
                ActiveMenuItem::ENQUEUED,
            ))
            ->addVar('queues', $queues)
            ->addVar('detailsUrlFactory', $this->detailsUrlFactory)
            ->extends(HttpPath::LAYOUT_INTERFACE);

        $response->getBody()->write($template->render());

        return $response;
    }
}
