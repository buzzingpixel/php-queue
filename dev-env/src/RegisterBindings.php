<?php

declare(strict_types=1);

namespace App;

use BuzzingPixel\Queue\Http\Routes\RoutesFactory;
use BuzzingPixel\Queue\QueueConfig;
use BuzzingPixel\Queue\QueueHandler;
use BuzzingPixel\Queue\RedisDriver\RedisQueueHandler;
use DateTimeZone;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Redis;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class RegisterBindings
{
    public function register(ContainerBindings $bindings): void
    {
        $bindings->addBinding(
            CacheItemPoolInterface::class,
            $bindings->resolveFromContainer(RedisAdapter::class),
        );

        $bindings->addBinding(
            RedisAdapter::class,
            static function (ContainerInterface $container): RedisAdapter {
                $redis = $container->get(Redis::class);
                assert($redis instanceof Redis);

                return new RedisAdapter($redis);
            },
        );

        $bindings->addBinding(
            Redis::class,
            static function (ContainerInterface $container): Redis {
                $redis = new Redis();

                $redis->connect('php_queue_dev_redis');

                return $redis;
            },
        );

        ////////////////////////////////////////////////////////////////////////

        $bindings->addBinding(
            RoutesFactory::class,
            $bindings->autowire(
                RoutesFactory::class,
            )->constructorParameter(
                'routePrefix',
                '/queue',
            ),
        );

        $bindings->addBinding(
            QueueHandler::class,
            $bindings->resolveFromContainer(
                RedisQueueHandler::class,
            ),
        );

        $bindings->addBinding(
            QueueConfig::class,
            $bindings->autowire(QueueConfig::class)->constructorParameter(
                'displayTimezone',
                new DateTimeZone('America/Chicago'),
            ),
        );
    }
}
