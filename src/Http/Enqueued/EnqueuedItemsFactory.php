<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\Http\Enqueued;

use BuzzingPixel\Queue\QueueHandler;
use Psr\Http\Message\ServerRequestInterface;

readonly class EnqueuedItemsFactory
{
    public function __construct(private QueueHandler $queueHandler)
    {
    }

    public function createFromRequest(
        ServerRequestInterface $request,
    ): EnqueuedItemsResult {
        $activeQueue = $request->getQueryParams()['queue'] ?? '';

        $queueItems = $this->queueHandler->getEnqueuedItemsFromAllQueues();

        if ($activeQueue === '') {
            return new EnqueuedItemsResult(
                $queueItems,
                $queueItems,
            );
        }

        return new EnqueuedItemsResult(
            $queueItems,
            $queueItems->filterWhereQueueNameIs(
                $activeQueue,
            ),
        );
    }
}
