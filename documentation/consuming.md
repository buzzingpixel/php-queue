# Consuming the Queue(s)

Ultimately, you need one or more runners that call the `consumeNext` method on the `QueueHandler` every second or 5 seconds or whatever your preference is. If you have more than one queue name available, you'll need at least one runner for each name. You can use something like supervisor to run a script and make sure it keeps running every second. Or you can set up a docker container that will restart if something goes wrong and runs the queue every second.

This package provides a Symfony console command which you can use if you're using [Symfony Console](https://symfony.com/doc/current/components/console.html) (or [Silly](https://github.com/mnapoli/silly), which is my preference). Load up `\BuzzingPixel\Queue\Framework\QueueConsumeNextSymfonyCommand` through your container, and add it to your Symfony console app.

Then run the command `buzzingpixel-queue:consume-next` through your CLI app. That consumes the `default` queue name. For consuming other queue names, use the `--queue-name=MY_QUEUE_NAME` argument.

## Changing the command name

If you'd like to change the command name, you can do so through the `name` constructor parameter of the `QueueConsumeNextSymfonyCommand` class. Configure your DI to provide the command name you would prefer.

## Example: Adding the command to a Symfony application

```php
<?php

use BuzzingPixel\Queue\Framework\QueueConsumeNextSymfonyCommand;
use DI\ContainerBuilder;
use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$di = (new ContainerBuilder())
    ->useAutowiring(true)
    ->addDefinitions([
        // ...definitions
    ])
    ->build();

$app = $di->get(Application::class);
assert($app instanceof Application);

$command = $di->get(QueueConsumeNextSymfonyCommand::class);
assert($command instanceof QueueConsumeNextSymfonyCommand);
$app->add($command);

$app->run();
```

## Example of adding the command to a Silly application

```php
<?php

use BuzzingPixel\Queue\Framework\QueueConsumeNextSymfonyCommand;
use DI\ContainerBuilder;
use Silly\Application;

$di = (new ContainerBuilder())
    ->useAutowiring(true)
    ->addDefinitions([
        // ...definitions
    ])
    ->build();

$app = new Application('My CLI');

$app->useContainer($di);

$command = $di->get(QueueConsumeNextSymfonyCommand::class);
assert($command instanceof QueueConsumeNextSymfonyCommand);
$app->add($command);

$app->run();
```

## Example: Creating a docker image to run the queue

You will need a base image to run your application. Most often, I have a PHP/NGINX image for the baseline image, which is run to serve web requests. Then, I build on that image to create the queue consumer image.

For a really basic example of a base app Dockerfile, see [docker/Dockerfile](../docker/Dockerfile), which is the base app image I use for development and running/testing things as I develop this package. This Dockerfile example will build from that:

```Dockerfile
ARG API_IMAGE=TAGNAME_OF_BASE_APP_IMAGE
FROM $API_IMAGE

ENTRYPOINT []

CMD printenv | grep -v "no_proxy" >> /etc/environment && /var/www/queueConsumer.sh
```

And here is the `queueConsumer.sh`, which in the above Dockerfile is assumed to be built into the Docker image at `/var/www`

```bash
#!/usr/bin/env bash

echo "Entering Queue Consume loop…";
while true; do
    /usr/local/bin/php /var/www/cli buzzingpixel-queue:consume-next --verbose --no-interaction;
    sleep 0.5;
done
```

## Example: Changing the command name

```php
use BuzzingPixel\Queue\Framework\QueueConsumeNextSymfonyCommand;
use DI\ContainerBuilder;

use function DI\autowire;

$di = (new ContainerBuilder())
    ->useAutowiring(true)
    ->addDefinitions([
        QueueConsumeNextSymfonyCommand::class => autowire(QueueConsumeNextSymfonyCommand::class)
            ->constructorParameter('name', 'queue:consume-next'),
    ])
    ->build();
```
