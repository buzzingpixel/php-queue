<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Completed;

use BuzzingPixel\Queue\QueueHandler;
use Psr\Http\Message\ServerRequestInterface;

readonly class CompletedItemsFactory
{
    public function __construct(private QueueHandler $queueHandler)
    {
    }

    public function createFromRequest(
        ServerRequestInterface $request,
    ): CompletedItemsResult {
        $activeQueue = $request->getQueryParams()['queue'] ?? '';

        $completedItems = $this->queueHandler->getCompletedItemsFromAllQueues();

        if ($activeQueue === '') {
            return new CompletedItemsResult(
                $completedItems,
                $completedItems,
            );
        }

        return new CompletedItemsResult(
            $completedItems,
            $completedItems->filterWhereQueueNameIs(
                $activeQueue,
            ),
        );
    }
}
