# HTTP Interface

This queueing system provides a very basic HTTP interface for admins/devs to be able to view and interact with enqueued, completed, and failed items. Please note that **you are responsible for access control**. When you add the routes, you must protect them in whatever way you see fit. Nothing in the code to create the HTML or JSON does anything to protect the endpoints. **Use responsibly!**

The long and the short of using the HTTP interface is you need to wire up the routes to serve the corresponding HTTP actions. The class `\BuzzingPixel\Queue\Http\Routes\RoutesFactory` creates a collection of all the routes, which you can use to create the routes in your application.

The HTTP actions are classes that can (and should) be loaded through the DI container and they use PSR interfaces for request/response.

## HTML or JSON?

If the request is made with the header `Accept: application/json` or the query string `?json` (or `?json=anything`) is in the URL, json will be returned for the route endpoint.

Otherwise, HTML is returned.

## Example: Setting up the routes in Slim 4

I use [Slim 4](https://www.slimframework.com/) pretty much exclusively, so here's an example of setting up the routes in Slim (which uses [FastRoute](https://github.com/nikic/FastRoute)).

```php
<?php

declare(strict_types=1);

use BuzzingPixel\Queue\Http\Routes\Route;
use BuzzingPixel\Queue\Http\Routes\RoutesFactory;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$di = (new ContainerBuilder())
    ->useAutowiring(true)
    ->addDefinitions([
        // ...definitions
    ])
    ->build();

AppFactory::setContainer($di);
$app = AppFactory::create();

$queueRoutesFactory = $di->get(RoutesFactory::class);
$queueRoutes = $queueRoutesFactory->create();
$queueRoutes->map(
    static function (Route $route) use ($app): void {
        $app->map(
            [$route->requestMethod->name],
            $route->pattern,
            $route->class,
        )->add(SomeAuthenticationMiddlwarePerhaps::class);
    },
);

$app->run();
```
