<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Enqueued;

use BuzzingPixel\Queue\Http\HttpPath;
use BuzzingPixel\Queue\Http\LayoutVarsFactory;
use BuzzingPixel\Queue\QueueItemWithKeyCollection;
use BuzzingPixel\Templating\TemplateEngineFactory;
use Psr\Http\Message\ResponseInterface;

readonly class RespondWithHtml
{
    public function __construct(
        private LayoutVarsFactory $layoutVarsFactory,
        private TemplateEngineFactory $templateEngineFactory,
    ) {
    }

    public function respond(
        QueueItemWithKeyCollection $queueItems,
        ResponseInterface $response,
    ): ResponseInterface {
        $template = $this->templateEngineFactory->create()
            ->templatePath(EnqueuedPath::ENQUEUED_INTERFACE)
            ->vars($this->layoutVarsFactory->createVars(
                'Enqueued Items',
            ))
            ->extends(HttpPath::LAYOUT_INTERFACE);

        $response->getBody()->write($template->render());

        return $response;
    }
}
