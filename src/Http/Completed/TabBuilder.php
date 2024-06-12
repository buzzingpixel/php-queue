<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Completed;

use BuzzingPixel\Queue\QueueNameWithCompletedItems;
use BuzzingPixel\Queue\QueueNameWithCompletedItemsCollection;
use BuzzingPixel\Templating\TemplateEngineFactory;
use Psr\Http\Message\ServerRequestInterface;

use function array_merge;

readonly class TabBuilder
{
    public function __construct(
        private TemplateEngineFactory $templateEngineFactory,
    ) {
    }

    public function render(
        QueueNameWithCompletedItemsCollection $allItems,
        ServerRequestInterface $request,
    ): string {
        $activeQueue = $request->getQueryParams()['queue'] ?? '';

        $tabs = new Tabs(array_merge(
            [
                new Tab(
                    'all',
                    '',
                    $activeQueue === '',
                ),
            ],
            $allItems->map(static fn (
                QueueNameWithCompletedItems $q,
            ) => new Tab(
                $q->queueName,
                $q->queueName,
                $activeQueue === $q->queueName,
            )),
        ));

        return $this->templateEngineFactory->create()
            ->templatePath(CompletedPath::TABS_INTERFACE)
            ->addVar('tabs', $tabs)
            ->render();
    }
}
