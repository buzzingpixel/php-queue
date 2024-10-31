# Drivers

At the moment, the only driver supplied with this package is the Redis driver. But if you want to supply your own driver, implement the `\BuzzingPixel\Queue\QueueHandler` interface and wire your container to supply that when the interface is requested.

Otherwise, wire `\BuzzingPixel\Queue\QueueHandler` to supply the `\BuzzingPixel\Queue\RedisDriver\RedisQueueHandler`.

PHP's `\Redis` and the Symfony `\Symfony\Component\Cache\Adapter\RedisAdapter` will need to also be available through the constructor/container.

## Example: Container Wiring using PHP-DI

```php
use BuzzingPixel\Queue\QueueHandler;
use BuzzingPixel\Queue\RedisDriver\RedisQueueHandler;
use DI\ContainerBuilder;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Redis;
use Symfony\Component\Cache\Adapter\RedisAdapter;

use function DI\autowire;
use function DI\get as resolveFromContainer;

$di = (new ContainerBuilder())
    ->useAutowiring(true)
    ->addDefinitions([
        CacheItemPoolInterface::class => resolveFromContainer(RedisAdapter::class),
        RedisAdapter::class => static function (ContainerInterface $di): RedisAdapter {
            $redis = $di->get(Redis::class);
            assert($redis instanceof Redis);

            return new RedisAdapter($redis);
        },
        Redis::class => static function (): Redis {
            $redis = new Redis();
            
            $redis->connect('host and other connection information here');
            
            return $redis;
        },
        QueueHandler::class => resolveFromContainer(RedisQueueHandler::class),
    ])
    ->build();
```
