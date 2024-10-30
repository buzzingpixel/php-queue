<?php

declare(strict_types=1);

namespace App;

use BuzzingPixel\Queue\Http\Routes\Route;
use BuzzingPixel\Queue\Http\Routes\RoutesFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\RouteCollectorProxyInterface;

class RegisterRoutes
{
    public function register(
        RouteCollectorProxyInterface $routes,
        ContainerInterface $di,
    ): void {
        $routes->get('/', function (
            RequestInterface $request,
            ResponseInterface $response,
            $args
        ) {
            $response->getBody()->write("Hello world!");
            return $response;
        });

        $queueRoutesFactory = $di->get(RoutesFactory::class);
        $queueRoutes = $queueRoutesFactory->create();
        $queueRoutes->map(
            static function (Route $route) use ($routes): void {
                $routes->map(
                    [$route->requestMethod->name],
                    $route->pattern,
                    $route->class,
                );
            },
        );
    }
}
