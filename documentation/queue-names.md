# Queue Names

You can set up multiple queue names to have runners dedicated to a specific name/channel. If you do not, a default will be used and you can set your runner(s) to consume that one default queue. If you wish to set up multiple queue names, implement `\BuzzingPixel\Queue\QueueNames` and set up your container to serve your implementation and/or provide that implementation to the QueueHandler.

## Example

```php
use BuzzingPixel\Queue\QueueNames;
use BuzzingPixel\Queue\QueueNamesDefault;
use DI\ContainerBuilder;

use function DI\get as resolveFromContainer

enum CustomQueueName
{
    case default;
    case email;
    case example;
    // ...etc.
}

readonly class CustomQueueNames extends QueueNamesDefault
{
    public function getAvailableQueues(): array
    {
        return array_map(
            static fn (CustomQueueName $name) => $name->name,
            CustomQueueName::cases(),
        );
    }
}

$di = (new ContainerBuilder())
    ->useAutowiring(true)
    ->addDefinitions([
        QueueNames::class => resolveFromContainer(CustomQueueNames::class);
    ])
    ->build();
```
