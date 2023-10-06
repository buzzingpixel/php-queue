# BuzzingPixel PHP Queue

A fairly simple php queue system you can add to nearly any PHP app that utilizes a PSR-11 container.

## Drivers

At the moment, the only driver supplied with this package is the Redis driver. But if you want to supply your own driver, just implement the `\BuzzingPixel\Queue\QueueHandler` interface and wire your container to supply that when the interface is requested.

Otherwise, wire `\BuzzingPixel\Queue\QueueHandler` to supply the `\BuzzingPixel\Queue\RedisDriver\RedisQueueHandler`. PHP's `\Redis` and the Symfony `\Symfony\Component\Cache\Adapter\RedisAdapter` will need to also be available through the constructor/container.

## Usage

### Queue Names

You can set up multiple queue names to have runners dedicated to a specific name/channel. If you do not, a default will be used and you can set your runner(s) to consume that one default queue. If you wish to set up multiple queue names, implement `\BuzzingPixel\Queue\QueueNames` and set up your container to serve your implementation and/or provide that implementation to the QueueHandler.

### Enqueuing

When you wish to enqueue something, call up the `\BuzzingPixel\Queue\QueueHandler` in your code and add a `\BuzzingPixel\Queue\QueueItem` via the `enqueue` method. You can optionally provide a queue name that you wish the `QueueItem` you're adding to run on.

### Consuming the queue

Ultimately, you need one or more runners that calls the `consumeNext` method on the `QueueHandler` every second or 5 seconds or whatever your preference is. If you have more than one queue name available, you'll need at least one runner for each name. You can use something like supervisor to run a script and make sure it keeps running every second. Or you can set up a docker container that will restart if something goes wrong and runs the queue every second.

This package provides a Symfony console command which you can use if you're using [Symfony Console](https://symfony.com/doc/current/components/console.html) (or [Silly](https://github.com/mnapoli/silly), which is my preference). Load up `\BuzzingPixel\Queue\Framework\QueueConsumeNextSymfonyCommand` through your container, and add it to your Symfony console app.

Then run the command `buzzingpixel-queue:consume-next` through your CLI app. That consumes the `default` queue name. For consuming other queue names, use the `--queue-name=MY_QUEUE_NAME` argument.

##### Changing the command name

If you'd like to change the command name, you can do so through the `name` constructor parameter of the `QueueConsumeNextSymfonyCommand` class. Configure your DI to provide the command name you would prefer.
