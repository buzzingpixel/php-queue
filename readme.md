# PHP Queue

A fairly simple php queue system you can add to nearly any PHP app that utilizes a PSR-11 container.

## Drivers

At the moment, the only driver supplied with this package is the Redis driver. But if you want to supply your own driver, just implement the `\BuzzingPixel\Queue\QueueHandler` interface and wire your container to supply that when the interface is requested.

Otherwise, wire `\BuzzingPixel\Queue\QueueHandler` to supply the `\BuzzingPixel\Queue\RedisDriver\RedisQueueHandler`. PHP's `\Redis` and the Symfony `\Symfony\Component\Cache\Adapter\RedisAdapter` will need to also be available through the constructor/container.

## Usage

### Queue Names

You can set up multiple queue names to have runners dedicated to a specific name/channel. If you do not, a default will be used and you can set your runner(s) to consume that one default queue. If you wish to set up multiple queue names, implement `\BuzzingPixel\Queue\QueueNames` and set up your container to service your implementation and/or provide that implementation to the QueueHandler.

### Enqueuing

When you wish to enqueue something, call up the `\BuzzingPixel\Queue\QueueHandler` in your code and add a `\BuzzingPixel\Queue\QueueItem` via the `enqueue` method. You can optionally provide a queue name that you wish the QueueItem you're adding to run on.

### Consuming the queue

TODO
