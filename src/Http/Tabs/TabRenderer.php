<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Tabs;

use BuzzingPixel\Templating\TemplateEngineFactory;

readonly class TabRenderer
{
    public function __construct(
        private TemplateEngineFactory $templateEngineFactory,
    ) {
    }

    public function render(Tabs $tabs): string
    {
        return $this->templateEngineFactory->create()
            ->templatePath(TabsPath::TABS_INTERFACE)
            ->addVar('tabs', $tabs)
            ->render();
    }

    public function renderWithTitle(string $title, Tabs $tabs): string
    {
        return $this->templateEngineFactory->create()
            ->templatePath(TabsPath::TABS_WITH_TITLE_INTERFACE)
            ->addVar('title', $title)
            ->addVar('tabs', $this->render($tabs))
            ->render();
    }
}
