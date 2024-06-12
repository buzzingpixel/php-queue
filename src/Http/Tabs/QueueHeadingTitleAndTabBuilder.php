<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Tabs;

use BuzzingPixel\Queue\QueueNameWithCompletedItems;
use BuzzingPixel\Queue\QueueNameWithCompletedItemsCollection;
use BuzzingPixel\Queue\QueueNameWithItems;
use BuzzingPixel\Queue\QueueNameWithItemsCollection;
use Psr\Http\Message\ServerRequestInterface;

use function array_merge;

readonly class QueueHeadingTitleAndTabBuilder
{
    public function __construct(private TabRenderer $tabRenderer)
    {
    }

    public function render(
        QueueNameWithItemsCollection|QueueNameWithCompletedItemsCollection $allItems,
        ServerRequestInterface $request,
        string $pageTitle,
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
                QueueNameWithItems|QueueNameWithCompletedItems $q,
            ) => new Tab(
                $q->queueName,
                $q->queueName,
                $activeQueue === $q->queueName,
            )),
        ));

        return $this->tabRenderer->renderWithTitle(
            $pageTitle,
            $tabs,
        );
    }
}
