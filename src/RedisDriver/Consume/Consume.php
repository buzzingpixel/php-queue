<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver\Consume;

use BuzzingPixel\Queue\QueueConfig;
use BuzzingPixel\Queue\QueueItemJob;
use BuzzingPixel\Queue\RedisDriver\EnqueuedItemByKey;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Throwable;

readonly class Consume
{
    public function __construct(
        private Lock $lock,
        private QueueConfig $config,
        private AddFailedItem $addFailedItem,
        private ContainerInterface $container,
        private AddCompletedItem $addCompletedItem,
        private EnqueuedItemByKey $enqueuedItemByKey,
    ) {
    }

    public function next(string $queueName): void
    {
        if (! $this->config->queueNames->nameIsValid($queueName)) {
            throw new RuntimeException(
                'Invalid queue name ' . $queueName,
            );
        }

        $this->config->logger->debug(
            'Queue preparing to consume next item, QueueName: ' . $queueName,
            ['queueName' => $queueName],
        );

        $lockResult = $this->lock->obtainForNext($queueName);

        $queueItemResult = $this->enqueuedItemByKey->find(
            $lockResult->consumeKeySymfony,
        );

        if (! $queueItemResult->hasResult) {
            $this->config->logger->debug(
                'Acquired key could not be retrieved: ' . $lockResult->consumeKeyRaw,
                ['key' => $lockResult->consumeKeyRaw],
            );

            $this->lock->release($lockResult);

            return;
        }

        try {
            $this->config->logger->debug(
                'Running QueueItem\'s jobs for key: ' . $lockResult->consumeKeyRaw,
                ['key' => $lockResult->consumeKeyRaw],
            );

            $queueItemResult->queueItem->jobs->map(
                function (QueueItemJob $job): void {
                    $class = $this->container->get($job->class);

                    /** @phpstan-ignore-next-line */
                    $class->{$job->method}($job->context);
                },
            );

            $this->config->logger->debug(
                'Jobs completed successfully for key: ' . $lockResult->consumeKeyRaw,
                ['key' => $lockResult->consumeKeyRaw],
            );

            $this->addCompletedItem->add($queueItemResult->queueItem);

            $this->lock->release($lockResult);
        } catch (Throwable $exception) {
            $this->config->logger->error(
                'The QueueItem jobs threw an exception: ' . $lockResult->consumeKeyRaw,
                [
                    'key' => $lockResult->consumeKeyRaw,
                    'exceptionMessage' => $exception->getMessage(),
                    'exceptionCode' => $exception->getCode(),
                    'exceptionFile' => $exception->getFile(),
                    'exceptionLine' => $exception->getLine(),
                ],
            );

            $this->addFailedItem->add(
                $queueItemResult->queueItem,
                $exception,
            );

            $this->lock->release($lockResult);
        }
    }
}
