<?php

declare(strict_types=1);

namespace BuzzingPixel\Queue;

use Lcobucci\Clock\SystemClock;
use Psr\Clock\ClockInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use function assert;

readonly class QueueConfig
{
    public ClockInterface $clock;

    public QueueNames $queueNames;

    public LoggerInterface $logger;

    public function __construct(
        ContainerInterface $container,
        // 3600 = 60 minutes
        public int $jobsExpiresAfterSeconds = 3600,
        // 604800 = 7 days
        public int $completedItemsExpireAfterSeconds = 604800,
    ) {
        if ($container->has(ClockInterface::class)) {
            $clock = $container->get(ClockInterface::class);
        } else {
            $clock = SystemClock::fromSystemTimezone();
        }

        assert($clock instanceof ClockInterface);

        if ($container->has(QueueNames::class)) {
            $queueNames = $container->get(QueueNames::class);
        } else {
            $queueNames = new QueueNamesDefault();
        }

        assert($queueNames instanceof QueueNames);

        if ($container->has(LoggerInterface::class)) {
            $logger = $container->get(LoggerInterface::class);
        } else {
            $logger = new NoOpLogger();
        }

        assert($logger instanceof LoggerInterface);

        $this->clock = $clock;

        $this->queueNames = $queueNames;

        $this->logger = $logger;
    }
}
