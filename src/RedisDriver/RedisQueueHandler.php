<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver;

use BuzzingPixel\Queue\QueueConfig;
use BuzzingPixel\Queue\QueueHandler;
use BuzzingPixel\Queue\QueueItem;
use BuzzingPixel\Queue\QueueItemJob;
use BuzzingPixel\Queue\QueueItemJobCollection;
use BuzzingPixel\Queue\QueueItemResult;
use BuzzingPixel\Queue\QueueItemWithKeyCollection;
use BuzzingPixel\Queue\QueueNameWithItems;
use BuzzingPixel\Queue\QueueNameWithItemsCollection;
use BuzzingPixel\Queue\RedisDriver\Consume\Consume;
use BuzzingPixel\Queue\RedisDriver\Consume\DeQueue;

use function array_map;
use function array_values;

readonly class RedisQueueHandler implements QueueHandler
{
    public function __construct(
        private Consume $consume,
        private DeQueue $deQueue,
        private Enqueue $enqueue,
        private QueueConfig $config,
        private TotalItems $totalItems,
        private EnqueuedItems $enqueuedItems,
        private EnqueuedItemByKey $enqueuedItemByKey,
    ) {
    }

    public function jobsExpiresAfterSeconds(): int
    {
        return $this->config->jobsExpiresAfterSeconds;
    }

    /** @inheritDoc */
    public function getAvailableQueues(): array
    {
        return $this->config->queueNames->getAvailableQueues();
    }

    public function getTotalItemsInAllQueues(): int
    {
        return $this->totalItems->inAllQueues();
    }

    public function getTotalItemsInQueue(string $queueName = 'default'): int
    {
        return $this->totalItems->inQueue($queueName);
    }

    /** @return array<array<string, string|int>> */
    public function getAvailableQueuesWithTotals(): array
    {
        return array_values(array_map(
            fn (string $queue) => [
                'queue' => $queue,
                'totalItemsInQueue' => $this->getTotalItemsInQueue(
                    $queue,
                ),
            ],
            $this->getAvailableQueues(),
        ));
    }

    public function getEnqueuedItems(
        string $queueName = 'default',
    ): QueueItemWithKeyCollection {
        return $this->enqueuedItems->inQueue($queueName);
    }

    public function findEnqueuedItemByKey(string $key): QueueItemResult
    {
        return $this->enqueuedItemByKey->find($key);
    }

    public function getEnqueuedItemsFromAllQueues(): QueueNameWithItemsCollection
    {
        $queues = [];

        foreach ($this->getAvailableQueues() as $queue) {
            $queues[] = new QueueNameWithItems(
                $queue,
                $this->getEnqueuedItems($queue),
            );
        }

        return new QueueNameWithItemsCollection($queues);
    }

    public function enqueue(
        QueueItem $queueItem,
        string $queueName = 'default',
    ): bool {
        return $this->enqueue->item(
            $queueItem,
            $queueName,
        );
    }

    /** @inheritDoc */
    public function enqueueJob(
        string $handle,
        string $name,
        string $class,
        string $method = '__invoke',
        array $context = [],
        string $queueName = 'default',
    ): bool {
        return $this->enqueue(
            new QueueItem(
                $handle,
                $name,
                new QueueItemJobCollection([
                    new QueueItemJob(
                        $class,
                        $method,
                        $context,
                    ),
                ]),
            ),
            $queueName,
        );
    }

    public function consumeNext(string $queueName = 'default'): void
    {
        $this->consume->next($queueName);
    }

    public function deQueue(string $key): bool
    {
        return $this->deQueue->one($key);
    }

    public function deQueueAllItems(string $queueName = 'default'): bool
    {
        return $this->deQueue->allItems($queueName);
    }
}
