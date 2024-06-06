<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

interface QueueHandler
{
    /** @return string[] */
    public function getAvailableQueues(): array;

    public function getTotalItemsInAllQueues(): int;

    public function getTotalItemsInQueue(string $queueName = 'default'): int;

    /** @return array<array<string, string|int>> */
    public function getAvailableQueuesWithTotals(): array;

    public function getEnqueuedItems(
        string $queueName = 'default',
    ): QueueItemWithKeyCollection;

    public function findEnqueuedItemByKey(string $key): QueueItemResult;

    public function getEnqueuedItemsFromAllQueues(): QueueNameWithItemsCollection;

    public function enqueue(
        QueueItem $queueItem,
        string $queueName = 'default',
    ): bool;

    /**
     * @param class-string $class
     * @param mixed[]      $context Must be `json_encode`-able
     */
    public function enqueueJob(
        string $handle,
        string $name,
        string $class,
        string $method = '__invoke',
        array $context = [],
        string $queueName = 'default',
    ): bool;

    public function deQueue(string $key): bool;

    public function deQueueAllItems(string $queueName = 'default'): bool;

    public function consumeNext(
        string $queueName = 'default',
    ): void;

    public function getCompletedJobs(
        string $queueName = 'default',
    ): QueueItemCompletedCollection;

    public function getCompletedJobsFromAllQueues(): QueueNameWithCompletedItemsCollection;

    public function getFailedJobs(
        string $queueName = 'default',
    ): QueueItemFailedCollection;

    public function getFailedJobsFromAllQueues(): QueueNameWithFailedItemsCollection;
}
