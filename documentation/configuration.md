# Configuration

The queue provides some config through the `\BuzzingPixel\Queue\QueueConfig` class. Any class that needs to find a config value uses constructor injection to get the config class, which means you can change configuration by changing its constructor values in your DI.

Here are two examples using PHP-DI

## Examples

One way is to return a new instance in the configuration of your DI.

```php
use BuzzingPixel\Queue\QueueConfig;
use DateTimeZone;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

use function DI\autowire;

$di = (new ContainerBuilder())
    ->useAutowiring(true)
    ->addDefinitions([
        QueueConfig::class => static function (ContainerInterface $di): QueueConfig {
            return new QueueConfig(
                // Container must be provided
                container: $di,

                // Optional. defaults to 3600 seconds (60 minutes).
                // If a job gets "hung", this is when it's considered expired
                jobsExpiresAfterSeconds: 1800,

                // Optional. Defaults top 604800 (7 days)
                // How long to retain a record of items after they are completed
                completedItemsExpireAfterSeconds: 259200,

                // Optional. Defaults to 604800 (7 days)
                // How long to retain a record of items after they fail
                failedItemsExpireAfterSeconds: 259200,

                // Optional. Defaults to `new DateTimeZone(date_default_timezone_get())`
                // Tells the HTTP user interface what timezone to display dates in
                displayTimezone: new DateTimeZone('America/Chicago'),

                // Optional. Defaults to 'Y-m-d h:i:s A e'
                // Tells the HTTP user interface what format to display dates in
                displayDateFormat: 'Y-m-d h:i:s A e',
            );
        }
    ])
    ->build();
```

However, you may also just want to let autowiring do its thing and set only the necessary constructor argument(s).

```php
use BuzzingPixel\Queue\QueueConfig;
use DateTimeZone;
use DI\ContainerBuilder;

use function DI\autowire;

$di = (new ContainerBuilder())
    ->useAutowiring(true)
    ->addDefinitions([
        QueueConfig::class => autowire(QueueConfig::class)
            ->constructorParameter(
                'displayTimezone',
                new DateTimeZone('America/Chicago'),
            ),
    ])
    ->build();
```
