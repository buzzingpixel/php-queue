<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\NotFound;

use BuzzingPixel\Queue\Http\HttpPath;
use BuzzingPixel\Queue\Http\LayoutVarsFactory;
use BuzzingPixel\Templating\TemplateEngineFactory;
use Psr\Http\Message\ResponseInterface;

readonly class RespondWith404Html
{
    public function __construct(
        private LayoutVarsFactory $layoutVarsFactory,
        private TemplateEngineFactory $templateEngineFactory,
    ) {
    }

    public function respond(ResponseInterface $response): ResponseInterface
    {
        $template = $this->templateEngineFactory->create()
            ->templatePath(NotFoundPath::NOT_FOUND_INTERFACE)
            ->vars($this->layoutVarsFactory->createVars(
                'Page Not Found',
            ))
            ->extends(HttpPath::LAYOUT_INTERFACE);

        $response->getBody()->write($template->render());

        return $response;
    }
}
