<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Failed;

use BuzzingPixel\Queue\QueueHandler;
use Psr\Http\Message\ServerRequestInterface;

readonly class FailedItemsFactory
{
    public function __construct(private QueueHandler $queueHandler)
    {
    }

    public function createFromRequest(
        ServerRequestInterface $request,
    ): FailedItemsResult {
        $activeQueue = $request->getQueryParams()['queue'] ?? '';

        $failedItems = $this->queueHandler->getFailedItemsFromAllQueues();

        if ($activeQueue === '') {
            return new FailedItemsResult(
                $failedItems,
                $failedItems,
            );
        }

        return new FailedItemsResult(
            $failedItems,
            $failedItems->filterWhereQueueNameIs(
                $activeQueue,
            ),
        );
    }
}
