<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue\RedisDriver;

use BuzzingPixel\Queue\NoOpLogger;
use BuzzingPixel\Queue\QueueHandler;
use BuzzingPixel\Queue\QueueItem;
use BuzzingPixel\Queue\QueueItemJob;
use BuzzingPixel\Queue\QueueNames;
use BuzzingPixel\Queue\QueueNamesDefault;
use DateTimeZone;
use Lcobucci\Clock\SystemClock;
use Psr\Clock\ClockInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\UuidFactory;
use Redis;
use ReflectionProperty;
use RuntimeException;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Throwable;

use function count;
use function explode;
use function implode;
use function is_string;
use function sort;

readonly class RedisQueueHandler implements QueueHandler
{
    private ClockInterface $clock;

    private LoggerInterface $logger;

    private QueueNames $queueNames;

    public function __construct(
        private Redis $redis,
        private RedisAdapter $cachePool,
        private UuidFactory $uuidFactory,
        private ContainerInterface $container,
        ClockInterface|null $clock = null,
        LoggerInterface|null $logger = null,
        private int $jobsExpireAfterSeconds = QueueHandler::JOBS_EXPIRES_AFTER_SECONDS,
        private bool $deleteQueueItemsOnFailure = true,
        QueueNames|null $queueNames = null,
    ) {
        $this->clock = $clock ?? new SystemClock(
            new DateTimeZone('UTC'),
        );

        $this->logger = $logger ?? new NoOpLogger();

        $this->queueNames = $queueNames ?? new QueueNamesDefault();
    }

    /** @inheritDoc */
    public function getAvailableQueues(): array
    {
        return $this->queueNames->getAvailableQueues();
    }

    public function jobsExpiresAfterSeconds(): int
    {
        return $this->jobsExpireAfterSeconds;
    }

    public function enqueue(
        QueueItem $queueItem,
        string $queueName = 'default',
    ): bool {
        if (! $this->queueNames->nameIsValid($queueName)) {
            throw new RuntimeException(
                'Invalid queue name ' . $queueName,
            );
        }

        $uid = $this->uuidFactory->uuid4()->toString();

        $handle = $queueItem->handle;

        $key = implode('_', [
            'queue',
            $queueName,
            $this->clock->now()->getTimestamp(),
            $handle,
            $uid,
        ]);

        $this->logger->debug(
            'Enqueueing item',
            [
                'handle' => $queueItem->handle,
                'name' => $queueItem->name,
            ],
        );

        return $this->cachePool->save(
            $this->cachePool->getItem($key)->set($queueItem),
        );
    }

    public function consumeNext(string $queueName = 'default'): void
    {
        if (! $this->queueNames->nameIsValid($queueName)) {
            throw new RuntimeException(
                'Invalid queue name ' . $queueName,
            );
        }

        $this->logger->debug(
            'Queue preparing to consume next item, QueueName: ' . $queueName,
            ['queueName' => $queueName],
        );

        $redisNamespaceProperty = new ReflectionProperty(
            AbstractAdapter::class,
            'namespace',
        );

        /** @noinspection PhpExpressionResultUnusedInspection */
        $redisNamespaceProperty->setAccessible(true);

        $namespace = $redisNamespaceProperty->getValue($this->cachePool);

        $namespace = is_string($namespace) ? $namespace : '';

        $enqueuedKeys = $this->redis->keys(
            $namespace . 'queue_' . $queueName . '_*',
        );

        sort($enqueuedKeys);

        $totalEnqueuedItems = count($enqueuedKeys);

        $this->logger->debug(
            'Enqueued items available: ' . $totalEnqueuedItems,
            [
                'total' => $totalEnqueuedItems,
                'keys' => $enqueuedKeys,
            ],
        );

        $lockKey    = '';
        $consumeKey = '';

        foreach ($enqueuedKeys as $key) {
            $lockKey = 'lock_' . $key;

            $lockObtained = $this->redis->setnx($lockKey, 'true');

            if (! $lockObtained) {
                continue;
            }

            $this->redis->expire(
                $lockKey,
                $this->jobsExpiresAfterSeconds(),
            );

            $consumeKey = $key;

            break;
        }

        if ($consumeKey === '') {
            return;
        }

        $consumeKeyArray = explode(':', $consumeKey);

        $consumeKeyNoNamespace = $consumeKeyArray[count($consumeKeyArray) - 1];

        $this->logger->debug(
            'Queue acquired lock on key ' . $consumeKey,
            ['key' => $consumeKey],
        );

        $consumeItemCache = $this->cachePool->getItem(
            $consumeKeyNoNamespace,
        );

        if (! $consumeItemCache->isHit()) {
            $this->logger->debug(
                'Acquired key could not be retrieved from cache: ' . $consumeKey,
                ['key' => $consumeKey],
            );

            $this->cachePool->deleteItem($consumeKeyNoNamespace);

            $this->redis->del($lockKey);

            return;
        }

        $consumeItem = $consumeItemCache->get();

        if (! ($consumeItem instanceof QueueItem)) {
            $this->logger->debug(
                'Acquired key from cache is not instance of QueueItem: ' . $consumeKey,
                ['key' => $consumeKey],
            );

            $this->redis->del($lockKey);

            $this->redis->del($consumeKey);

            return;
        }

        try {
            $this->logger->debug(
                'Running QueueItem\'s jobs for key: ' . $consumeKey,
                ['key' => $consumeKey],
            );

            $consumeItem->jobs->map(
                function (QueueItemJob $job): void {
                    $class = $this->container->get($job->class);

                    /** @phpstan-ignore-next-line */
                    $class->{$job->method}($job->context);
                },
            );

            $this->logger->debug(
                'Jobs completed successfully for key: ' . $consumeKey,
                ['key' => $consumeKey],
            );

            $this->redis->del($lockKey);

            $this->cachePool->deleteItem($consumeKeyNoNamespace);
        } catch (Throwable $exception) {
            $this->logger->error(
                'The QueueItem jobs threw an exception: ' . $consumeKey,
                [
                    'key' => $consumeKey,
                    'exceptionMessage' => $exception->getMessage(),
                    'exceptionCode' => $exception->getCode(),
                    'exceptionFile' => $exception->getFile(),
                    'exceptionLine' => $exception->getLine(),
                ],
            );

            $this->redis->del($lockKey);

            if (! $this->deleteQueueItemsOnFailure) {
                return;
            }

            $this->redis->del($consumeKey);
        }
    }
}
