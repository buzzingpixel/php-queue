# Enqueuing

When you wish to enqueue something, call up the `\BuzzingPixel\Queue\QueueHandler` in your code and add a `\BuzzingPixel\Queue\QueueItem` via the `enqueue` method or the `enqueueJob` method. You can optionally provide a queue name that you wish the `QueueItem` you're adding to run on.

## `enqueueJob`

Use `enqueueJob` when you want to add a single job onto a queue. This is a convenience method that creates a single `QueueItem` with a single `QueueItemJob`.

### Example of `enqueueJob`

```php
use BuzzingPixel\Queue\QueueHandler;

readonly class MyClass
{
    public function __construct(private QueueHandler $queueHandler)
    {
    }
    
    public function doSomething()
    {
        $this->queueHandler->enqueueJob(
            handle: 'my-handle',
            name: 'My Human Readable Name',
            class: MyJob::class,
            method: 'someMethod', // defaults to __invoke
            context: [], // Optional, send an array as an argument to the method
            queueName: CustomQueueName::example, // Optionally add to a queue name
        );
    }
}
```

## `enqueue`

Use `enqueue` when you want to build a series of jobs that must run one after the other in sequence. Items in the queue can be run asynchronously by multiple runners, but an item's jobs will run in the order they are listed on the item.

### Example of `enqueue`

```php
use BuzzingPixel\Queue\QueueHandler;
use BuzzingPixel\Queue\QueueItem;
use BuzzingPixel\Queue\QueueItemJob;
use BuzzingPixel\Queue\QueueItemJobCollection;

readonly class MyClass
{
    public function __construct(private QueueHandler $queueHandler)
    {
    }
    
    public function doSomething()
    {
        $this->queueHandler->enqueue(
            queueItem: new QueueItem(
                handle: 'my-handle',
                name: 'My Human Readable Name',
                jobs: new QueueItemJobCollection([
                    new QueueItemJob(
                        class: MyJob::class,
                        method: 'someMethod', // defaults to __invoke
                        context: [], // Optional, send an array as an argument to the method
                    ),
                    new QueueItemJob(
                        class: AnotherJob::class,
                    ),
                ]),
            ),
            queueName: CustomQueueName::example, // Optionally add to a queue name
        );
    }
}
```
